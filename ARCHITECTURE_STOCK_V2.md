# Architecture de Gestion de Stock Cumulatif - Déchet

## Principes Métier

1. **Produit = Catalogue uniquement**  
   - Le Produit ne crée jamais automatiquement un Stock  
   - Un Stock doit être créé manuellement via l'inventaire  

2. **Un seul Stock global par Produit**  
   - Un produit = une ligne de stock unique  
   - Pas de lots, pas de FIFO  

3. **Stock Cumulatif**  
   - Les entrées augmentent le stock  
   - Les sorties diminuent le stock  
   - Impossible d'avoir un stock négatif  

4. **Prix Moyen Pondéré**  
   - À chaque entrée, recalcul du prix unitaire  
   - Formula: `(ancienne_quantité × ancien_prix + quantité_entrée × prix_entrée) / quantité_totale`

---

## Schéma de Base de Données

### Tables Principales

#### `produits`  
```
id (PK)
nom (string, unique)
type (string)
unite_mesure (string, default: 'kg')
prix_unitaire (decimal, nullable)
description (text)
statut (enum: actif, inactif)
photo (string, nullable)
trie_id (FK → tries, nullable)
created_at, updated_at
```

#### `stocks`  
```
id (PK)
code_stock (string, unique)
nom (string)
quantite_disponible (decimal, default: 0)
prix_unitaire (decimal)
unite_mesure (string)
seuil_alerte (decimal)
produit_id (FK → produits, unique) ← **UNE SEULE ENTRÉE PAR PRODUIT**
trie_id (FK → tries, nullable)
created_at, updated_at
```

#### `mouvements`  
```
id (PK)
stock_id (FK → stocks)
type_mouvement (enum: 'entree', 'sortie')
quantite (decimal)
prix_unitaire (decimal)
montant_total (decimal)
source (string, ex: 'Production', 'Retour client', 'Commande')
description (text)
commande_id (FK → commandes, nullable)
user_id (FK → users)
date_mouvement (date)
heure_mouvement (time)
created_at, updated_at
```

#### `commandes`  
```
id (PK)
code_commande (string, unique)
produit_id (FK → produits)
quantite (integer)
prix_unitaire (decimal)
montant_total (decimal)
statut (enum: en_attente, acceptee, refusee, livree)
client_id (FK → clients)
date_commande (date)
created_at, updated_at
```

---

## Modèles Eloquent

### `Stock` Model

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'code_stock',
        'nom',
        'quantite_disponible',
        'prix_unitaire',
        'unite_mesure',
        'seuil_alerte',
        'produit_id',
        'trie_id',
    ];

    protected $casts = [
        'quantite_disponible' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'seuil_alerte' => 'decimal:2',
    ];

    // Relations
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(Mouvement::class, 'stock_id');
    }

    // Ajouter une quantité (entrée)
    public function addQuantity(float $quantity, float $prixUnitaire, string $source, ?string $description = null): Mouvement
    {
        if ($quantity <= 0) {
            throw new \\InvalidArgumentException('La quantité doit être supérieure à zéro.');
        }

        return DB::transaction(function () use ($quantity, $prixUnitaire, $source, $description) {
            $ancienneQuantite = $this->quantite_disponible;
            $ancienPrix = $this->prix_unitaire;

            $ancienneValeur = $ancienneQuantite * $ancienPrix;
            $nouvelleValeur = $quantity * $prixUnitaire;
            $quantiteTotale = $ancienneQuantite + $quantity;

            // Calcul du prix moyen pondéré
            $this->quantite_disponible = $quantiteTotale;
            $this->prix_unitaire = $quantiteTotale > 0
                ? ($ancienneValeur + $nouvelleValeur) / $quantiteTotale
                : $prixUnitaire;
            $this->save();

            // Créer le mouvement automatiquement
            return $this->mouvements()->create([
                'type_mouvement'  => 'entree',
                'quantite'        => $quantity,
                'prix_unitaire'   => $prixUnitaire,
                'montant_total'   => $nouvelleValeur,
                'source'          => $source,
                'description'     => $description,
                'date_mouvement'  => now()->toDateString(),
                'heure_mouvement' => now()->format('H:i:s'),
                'user_id'         => auth()->id(),
            ]);
        });
    }

    // Retirer une quantité (sortie)
    public function removeQuantity(float $quantity, string $source, ?string $description = null, ?Commande $commande = null): Mouvement
    {
        if ($quantity <= 0) {
            throw new \\InvalidArgumentException('La quantité doit être supérieure à zéro.');
        }

        if ($this->quantite_disponible < $quantity) {
            throw new \\InvalidArgumentException('Stock insuffisant.');
        }

        return DB::transaction(function () use ($quantity, $source, $description, $commande) {
            $this->decrement('quantite_disponible', $quantity);

            return $this->mouvements()->create([
                'type_mouvement'  => 'sortie',
                'quantite'        => $quantity,
                'prix_unitaire'   => $this->prix_unitaire,
                'montant_total'   => $quantity * $this->prix_unitaire,
                'source'          => $source,
                'description'     => $description,
                'commande_id'     => $commande?->id,
                'date_mouvement'  => now()->toDateString(),
                'heure_mouvement' => now()->format('H:i:s'),
                'user_id'         => auth()->id(),
            ]);
        });
    }

    // Scopes
    public function scopeDisponible($query)
    {
        return $query->where('quantite_disponible', '>', 0);
    }

    public function scopeEnAlerte($query)
    {
        return $query->whereColumn('quantite_disponible', '<=', 'seuil_alerte');
    }

    // Accesseurs
    public function getValeurTotaleAttribute(): float
    {
        return round($this->quantite_disponible * $this->prix_unitaire, 2);
    }

    public function getStatutAttribute(): string
    {
        if ($this->quantite_disponible <= 0) {
            return 'Rupture';
        }
        if ($this->quantite_disponible <= $this->seuil_alerte) {
            return 'En alerte';
        }
        return 'Disponible';
    }
}
```

### `Mouvement` Model

```php
<?php
namespace App\Models;

class Mouvement extends Model
{
    protected $fillable = [
        'stock_id',
        'type_mouvement',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'source',
        'description',
        'commande_id',
        'user_id',
        'date_mouvement',
        'heure_mouvement',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'date_mouvement' => 'date',
    ];

    public function setTypeMouvementAttribute($value): void
    {
        $normalized = strtolower(trim($value));
        if ($normalized === 'entrée') {
            $normalized = 'entree';
        }
        $this->attributes['type_mouvement'] = in_array($normalized, ['entree', 'sortie'])
            ? $normalized
            : 'entree';
    }

    // Relations
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeEntrees($query)
    {
        return $query->where('type_mouvement', 'entree');
    }

    public function scopeSorties($query)
    {
        return $query->where('type_mouvement', 'sortie');
    }
}
```

### `Commande` Model

```php
<?php
namespace App\Models;

class Commande extends Model
{
    protected $fillable = [
        'code_commande',
        'produit_id',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'statut',
        'client_id',
        'date_commande',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'montant_total' => 'decimal:2',
        'date_commande' => 'date',
    ];

    // Relations
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(Mouvement::class);
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAcceptee($query)
    {
        return $query->where('statut', 'acceptee');
    }
}
```

### `Produit` Model

```php
<?php
namespace App\Models;

class Produit extends Model
{
    protected $fillable = [
        'nom',
        'type',
        'unite_mesure',
        'prix_unitaire',
        'description',
        'statut',
        'photo',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
    ];

    // Relations
    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }
}
```

---

## Gestion des Commandes

### Validation de la Commande

```php
// Dans CommandeClientController::commander()

$stock = $produit->stock;

if (!$stock || $stock->quantite_disponible < $request->quantite) {
    return back()->with('error', 'Stock insuffisant.');
}

Commande::create([
    'code_commande' => 'CMD-' . strtoupper(Str::random(8)),
    'produit_id'    => $produit->id,
    'quantite'      => $request->quantite,
    'prix_unitaire' => $produit->prix_unitaire,
    'montant_total' => $produit->prix_unitaire * $request->quantite,
    'statut'        => 'en_attente',
    'client_id'     => $client->id,
    'date_commande' => now(),
]);
```

### Acceptation de la Commande

```php
// Dans CommandeAdminController::accepter()

if ($commande->statut !== 'en_attente') {
    return back()->with('error', 'Commande non en attente.');
}

$stock = $commande->produit->stock;

if (!$stock || $stock->quantite_disponible < $commande->quantite) {
    return back()->with('error', 'Stock insuffisant pour accepter cette commande.');
}

DB::transaction(function () use ($commande, $stock) {
    // Sortir le stock
    $stock->removeQuantity(
        $commande->quantite,
        'Commande validée',
        'Commande acceptée - ' . $commande->code_commande,
        $commande
    );

    // Créer paiement
    Paiement::create([
        'commande_id'   => $commande->id,
        'mode_paiement' => 'en_ligne',
        'montant'       => $commande->montant_total,
        'statut'        => 'valide',
    ]);

    // Marquer la commande comme acceptée
    $commande->update(['statut' => 'acceptee']);
});
```

---

## Gestion de l'Inventaire

### Créer un nouveau Stock

```php
// Dans InventaireController::store()

$stock = Stock::create([
    'code_stock'          => 'STK-' . strtoupper(Str::random(8)),
    'nom'                 => Produit::find($validated['produit_id'])->nom,
    'quantite_disponible' => 0,
    'prix_unitaire'       => $validated['prix_unitaire'],
    'unite_mesure'        => $validated['unite_mesure'],
    'seuil_alerte'        => $validated['seuil_alerte'],
    'produit_id'          => $validated['produit_id'],
]);

// Ajouter la première quantité
$stock->addQuantity(
    $validated['quantite_disponible'],
    $validated['prix_unitaire'],
    'Entrée manuelle',
    'Création du stock'
);
```

### Ajouter une Quantité à un Stock Existant

```php
$stock->addQuantity(
    $quantite,
    $prix_unitaire,
    $source, // ex: 'Production', 'Retour client', 'Fournisseur'
    $description
);
```

### Retirer une Quantité

```php
try {
    $stock->removeQuantity(
        $quantite,
        $source,
        $description
    );
} catch (\\InvalidArgumentException $e) {
    return back()->with('error', $e->getMessage()); // 'Stock insuffisant.'
}
```

### Ajuster l'Inventaire

```php
// Augmentation
if ($diff > 0) {
    $stock->addQuantity($diff, $nouveau_prix, 'Ajustement inventaire', 'Correction');
}

// Diminution
if ($diff < 0) {
    $stock->removeQuantity(abs($diff), 'Ajustement inventaire', 'Correction');
}
```

---

## Observer Produit

**L'observateur ne crée PLUS de stock automatiquement.**

```php
<?php
namespace App\Observers;

use App\Models\Produit;
use App\Models\Stock;

class ProduitObserver
{
    // Removed created() hook - stock creation is manual

    public function updated(Produit $produit): void
    {
        $stock = Stock::where('produit_id', $produit->id)->first();

        if ($stock) {
            $stock->update([
                'nom'           => $produit->nom,
                'prix_unitaire' => $produit->prix_unitaire,
                'unite_mesure'  => $produit->unite_mesure,
            ]);
        }
    }

    public function deleted(Produit $produit): void
    {
        Stock::where('produit_id', $produit->id)->delete();
    }
}
```

---

## Migrations Clés

### Produits (Nettoyé)

```php
Schema::create('produits', function (Blueprint $table) {
    $table->id();
    $table->string('nom')->unique();
    $table->string('type');
    $table->string('unite_mesure')->default('kg');
    $table->decimal('prix_unitaire', 12, 2)->nullable();
    $table->text('description')->nullable();
    $table->string('statut')->default('actif');
    $table->string('photo')->nullable();
    $table->timestamps();
    $table->index(['type', 'statut']);
});
```

### Stocks (Unique par Produit)

```php
Schema::create('stocks', function (Blueprint $table) {
    $table->id();
    $table->string('code_stock')->unique();
    $table->string('nom');
    $table->decimal('quantite_disponible', 15, 2)->default(0);
    $table->decimal('prix_unitaire', 15, 2);
    $table->string('unite_mesure')->default('kg');
    $table->decimal('seuil_alerte', 12, 2)->default(100);
    $table->foreignId('produit_id')
        ->unique() // ← UNE SEULE ENTRÉE PAR PRODUIT
        ->constrained('produits')
        ->onDelete('cascade');
    $table->timestamps();
    $table->index('quantite_disponible');
});
```

### Mouvements

```php
Schema::create('mouvements', function (Blueprint $table) {
    $table->id();
    $table->enum('type_mouvement', ['entree', 'sortie'])->index();
    $table->decimal('quantite', 15, 2);
    $table->decimal('prix_unitaire', 15, 2);
    $table->decimal('montant_total', 15, 2);
    $table->string('source');
    $table->text('description')->nullable();
    $table->foreignId('commande_id')->nullable()
        ->constrained('commandes')
        ->onDelete('set null');
    $table->foreignId('stock_id')
        ->constrained('stocks')
        ->onDelete('cascade');
    $table->foreignId('user_id')
        ->constrained('users')
        ->onDelete('cascade');
    $table->date('date_mouvement')->nullable()->index();
    $table->time('heure_mouvement')->nullable();
    $table->timestamps();
    $table->index(['stock_id', 'date_mouvement']);
});
```

---

## Exemple d'Utilisation Complète

```php
// 1. Créer un produit (pas de stock auto)
$produit = Produit::create([
    'nom' => 'Pavé Plastique',
    'type' => 'matière_première',
    'unite_mesure' => 'kg',
    'prix_unitaire' => 25.00,
]);

// 2. Créer manuellement un stock
$stock = Stock::create([
    'code_stock' => 'STK-' . Str::random(6),
    'nom' => 'Pavé Plastique',
    'quantite_disponible' => 0,
    'prix_unitaire' => 25.00,
    'unite_mesure' => 'kg',
    'seuil_alerte' => 100,
    'produit_id' => $produit->id,
]);

// 3. Entrée 1 : +50 kg à 25€ = Stock = 50
$stock->addQuantity(50, 25.00, 'Production', 'Batch 1');

// 4. Entrée 2 : +30 kg à 26€ = Stock = 80, Nouveau prix = 25.50€
$stock->addQuantity(30, 26.00, 'Production', 'Batch 2');

// 5. Entrée 3 : +20 kg à 24€ = Stock = 100, Nouveau prix = 25.25€
$stock->addQuantity(20, 24.00, 'Production', 'Batch 3');

// 6. Sortie : -40 kg = Stock = 60
$stock->removeQuantity(40, 'Commande', 'Cmd-001');

// 7. Vérifier le stock cumulatif
echo $stock->quantite_disponible; // 60

// 8. Afficher les mouvements
$stock->mouvements()->get();
```

---

## Points Clés

✅ **Produit = Catalogue seul**  
✅ **Un Stock par Produit (unique constraint)**  
✅ **Stock cumulatif (entrée/sortie)**  
✅ **Prix moyen pondéré**  
✅ **Validation du stock avant sortie**  
✅ **Commandes validées = sortie auto**  
✅ **Impossible de sortir plus que disponible**  
✅ **Tous les mouvements tracés avec date/heure/user**  

---

**Version:** 2.0  
**Date:** Juin 2026  
**Auteur:** Architecture métier - Déchet

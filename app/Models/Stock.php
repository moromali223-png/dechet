<?php

namespace App\Models;

use App\Models\Commande;
use App\Models\Mouvement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    
    protected $fillable = [
      
        'code_stock',
        'quantite_disponible',
        'prix_unitaire',
        'unite_mesure',
        'seuil_alerte',
        'produit_id',
        'trie_id',
    ];

    protected $casts = [
        'quantite_disponible' => 'decimal:2',
        'prix_unitaire'       => 'decimal:2',
        'seuil_alerte'        => 'decimal:2',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];

    protected $appends = [
        'valeur_totale',
        'statut',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    public function trie(): BelongsTo
    {
        return $this->belongsTo(Trie::class, 'trie_id');
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(Mouvement::class, 'stock_id');
    }

    public function addQuantity(float $quantity, float $prixUnitaire, string $source, ?string $description = null): Mouvement
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('La quantité doit être supérieure à zéro.');
        }

        return DB::transaction(function () use ($quantity, $prixUnitaire, $source, $description) {
            $ancienneQuantite = $this->quantite_disponible;
            $ancienPrix = $this->prix_unitaire;

            $ancienneValeur = $ancienneQuantite * $ancienPrix;
            $nouvelleValeur = $quantity * $prixUnitaire;
            $quantiteTotale = $ancienneQuantite + $quantity;

            $this->quantite_disponible = $quantiteTotale;
            $this->prix_unitaire = $quantiteTotale > 0
                ? ($ancienneValeur + $nouvelleValeur) / $quantiteTotale
                : $prixUnitaire;
            $this->save();

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

    public function removeQuantity(float $quantity, string $source, ?string $description = null, ?Commande $commande = null): Mouvement
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('La quantité doit être supérieure à zéro.');
        }

        if ($this->quantite_disponible < $quantity) {
            throw new \InvalidArgumentException('Stock insuffisant.');
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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeDisponible($query)
    {
        return $query->where('quantite_disponible', '>', 0);
    }

    public function scopeEnAlerte($query)
    {
        return $query->whereColumn(
            'quantite_disponible',
            '<=',
            'seuil_alerte'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getValeurTotaleAttribute()
    {
        return round(
            ($this->quantite_disponible * $this->prix_unitaire),
            2
        );
    }

    public function getStatutAttribute()
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
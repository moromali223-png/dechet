<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produits';

    protected $fillable = [
        'nom',
        'type',
        'unite_mesure',
        'quantite',
        'prix_unitaire',
        'description',
        'statut',
        'trie_id',
    ];

    /**
     * Casts pour bien gérer les types de données
     */
    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'quantite' => 'decimal:2',
    ];

    /**
     * Relation avec Trie
     * Un produit appartient à un tri (selon ta migration actuelle)
     */
    public function trie(): BelongsTo
    {
        return $this->belongsTo(Trie::class);
    }

    /**
     * Un produit a un enregistrement de stock
     */
    public function stock()
    {
        return $this->hasOne(Stock::class, 'produit_id');
    }
    /**
     * Relation avec Stock (très importante selon ton diagramme)
     * Un produit peut avoir plusieurs entrées/mouvements de stock
     */

    /**
     * Accesseur : Afficher le nom + quantité + unité (utile dans les vues)
     */
    public function getNomCompletAttribute()
    {
        return $this->nom.' ('.$this->unite_mesure.')';
    }

    /**
     * Scope pour récupérer seulement les produits actifs
     */
    public function scopeActif($query)
    {
        return $query->whereIn('statut', ['actif', 'DISPONIBLE']);
    }
}

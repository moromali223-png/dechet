<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Mouvement extends Model
{
    protected $table = 'mouvements';

    protected $fillable = [
        'stock_id',
        'produit_id', // Supprimer cette ligne si la colonne n'existe pas dans la table mouvements
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
        'quantite'       => 'decimal:2',
        'prix_unitaire'  => 'decimal:2',
        'montant_total'  => 'decimal:2',
        'date_mouvement' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Mutator
    |--------------------------------------------------------------------------
    */

    public function setTypeMouvementAttribute($value): void
    {
        $value = Str::lower(trim((string) $value));

        if (in_array($value, ['entree', 'entrée', 'entré', 'entry'])) {
            $this->attributes['type_mouvement'] = 'entree';
        } elseif (in_array($value, ['sortie', 'sorti', 'exit'])) {
            $this->attributes['type_mouvement'] = 'sortie';
        } else {
            $this->attributes['type_mouvement'] = 'entree';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */

    public function getTypeMouvementLabelAttribute(): string
    {
        return match ($this->type_mouvement) {
           'entree', 'entrée' => 'Entrée',
            'sortie' => 'Sortie',
            default  => 'Inconnu',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeEntrees($query)
    {
        return $query->where('type_mouvement', 'entree');
    }

    public function scopeSorties($query)
    {
        return $query->where('type_mouvement', 'sortie');
    }
}
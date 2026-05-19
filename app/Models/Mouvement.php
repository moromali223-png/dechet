<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mouvement extends Model
{
    protected $table = 'mouvements';

    protected $fillable = [
        'stock_id',
        'produit_id',
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
        'date_mouvement' => 'datetime',
    ];

    /*
    |--------------------------------
    | MUTATOR
    |--------------------------------
    */
    public function setTypeMouvementAttribute($value)
    {
        $this->attributes['type_mouvement'] = in_array($value, ['entree', 'sortie'])
            ? $value
            : 'entree';
    }

    /*
    |--------------------------------
    | ACCESSOR
    |--------------------------------
    */
    public function getTypeMouvementLabelAttribute()
    {
        return match ($this->type_mouvement) {
            'sortie' => 'Sortie',
            'entree' => 'Entrée',
            default  => 'Inconnu',
        };
    }

    public function getMontantFormatteAttribute()
    {
        return number_format($this->montant_total, 2, ',', ' ');
    }

    /*
    |--------------------------------
    | RELATIONS
    |--------------------------------
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
    |--------------------------------
    | SCOPES
    |--------------------------------
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
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

    protected $hidden = ['user_id']; // Cache l'ID pour les API/Clients

    protected $casts = [
        'quantite' => 'decimal:2',
        'date_mouvement' => 'date',
    ];

    /*
    |-------------------------
    | MUTATOR PROPRE
    |-------------------------
    */

    public function setTypeMouvementAttribute($value)
    {
        $this->attributes['type_mouvement'] = strtolower($value) === 'sortie'
            ? 'sortie'
            : 'entree';
    }

    /*
    |-------------------------
    | ACCESSOR
    |-------------------------
    */

    public function getTypeMouvementLabelAttribute()
    {
        return $this->type_mouvement === 'sortie'
            ? 'Sortie'
            : 'Entrée';
    }

    /*
    |-------------------------
    | RELATIONS
    |-------------------------
    */

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
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
    |-------------------------
    | SCOPES
    |-------------------------
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
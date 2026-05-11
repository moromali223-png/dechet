<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_stock',
        'nom',
        'quantite_disponible',
        'prix_unitaire',
        'unite_mesure',
        'seuil_alerte',
        'produit_id',
        'commande_id',
    ];

    protected $casts = [
        'quantite_disponible' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'seuil_alerte' => 'decimal:2',
    ];

    /*
    |-------------------------
    | RELATIONS
    |-------------------------
    */

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function mouvements()
    {
        return $this->hasMany(Mouvement::class);
    }

    /*
    |-------------------------
    | SCOPES
    |-------------------------
    */

    public function scopeEnAlerte($query)
    {
        return $query->whereColumn('quantite_disponible', '<=', 'seuil_alerte');
    }

    /*
    |-------------------------
    | ACCESSORS
    |-------------------------
    */

    public function getValeurTotaleAttribute()
    {
        return $this->quantite_disponible * $this->prix_unitaire;
    }
}
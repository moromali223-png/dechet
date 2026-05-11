<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type',
        'quantite',
        'unite_mesure',
        'prix_unitaire',
        'description',
        'statut',
        'trie_id'
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------
    | RELATIONS
    |--------------------------------------------------
    */

    public function trie()
    {
        return $this->belongsTo(Trie::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    /*
    |--------------------------------------------------
    | SCOPES
    |--------------------------------------------------
    */

    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }
}
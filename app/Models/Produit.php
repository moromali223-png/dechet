<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'trie_id',
        'photo',
        'categorie',
        'stock_disponible',
    ];
protected $casts = [
    'prix_unitaire' => 'decimal:2',
    'quantite' => 'integer',
];

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return asset('storage/'.$this->photo);
        }

        return asset('images/produit-default.svg');
    }

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

    public function ligneCommandes()
    {
        return $this->hasMany(LigneCommande::class);
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

    public function commandes()
{
    return $this->hasMany(Commande::class);
}
public function produitRelation()
{
    return $this->belongsTo(Produit::class, 'produit_id');
}
}

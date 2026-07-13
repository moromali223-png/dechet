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
        'photo',
        
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return asset('storage/' . $this->photo);
        }

        return asset('images/produit-default.svg');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function ligneCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    // public function trie()
    // {
    //     return $this->belongsTo(Trie::class);
    // }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }
}
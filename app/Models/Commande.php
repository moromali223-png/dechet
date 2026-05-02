<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commande extends Model
{
    use HasFactory;

    protected $table = 'commandes';

    protected $fillable = [
        'code_commande',
        'produit',           // temporaire
        'produit_id',
        'quantite',
        'statut',
        'client_id',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',

    ];

    /**
     * Relation avec Client
     * Une commande appartient à un client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Si tu veux améliorer plus tard : relation avec Produit
     * (mieux qu'un simple string 'produit')
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    /**
     * Scopes utiles
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAcceptee($query)
    {
        return $query->where('statut', 'acceptee');
    }
}

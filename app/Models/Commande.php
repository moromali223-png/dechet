<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $table = 'commandes';

    protected $fillable = [
        'code_commande',
        'produit',       // garder si colonne existe
        'produit_id',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'statut',
        'client_id',
        'date_commande',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'montant_total' => 'decimal:2',
        'date_commande' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function client()
{
    return $this->belongsTo(Client::class);
}

// Relation principale
// Dans App\Models\Commande.php

public function produitRelation()
{
    return $this->belongsTo(Produit::class, 'produit_id');
}

/**
 * Alias important pour éviter le conflit avec la colonne "produit"
 */
public function produit()
{
    return $this->produitRelation();
}


public function paiements()
{
    return $this->hasMany(Paiement::class);
}
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAcceptee($query)
    {
        return $query->where('statut', 'acceptee');
    }

    public function scopeRefusee($query)
    {
        return $query->where('statut', 'refusee');
    }

    public function scopeLivree($query)
    {
        return $query->where('statut', 'livree');
    }
}
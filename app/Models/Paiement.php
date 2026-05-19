<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'mode_paiement',
        'montant',
        'statut',
        'type_paiement',
        'reference_paiement',
        'abonnement_id',
        'commande_id',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class);
    }
}

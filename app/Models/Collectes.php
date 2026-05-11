<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Collectes extends Model
{
    protected $table = 'collectes';

    protected $fillable = [
        'planification_id',
        'photo',
        'commentaire',
        'statut',
        'heure_depart',
        'heure_fin',
    ];

    /**
     * Une collecte appartient à une Planification
     */
    public function planification(): BelongsTo
    {
        return $this->belongsTo(Planification::class);
    }

    /**
     * Une collecte appartient à un Client (via la Planification)
     */
    public function client()
    {
        return $this->hasOneThrough(
            Client::class,
            Planification::class,
            'id',           // Clé primaire de Planification
            'abonnement_id', // Colonne dans Planification qui pointe vers Client (à confirmer)
            'planification_id', // Clé étrangère dans Collectes
            'id'            // Clé primaire de Client
        );
    }

    /**
     * Relation avec le pesage
     */
    public function pesage(): HasOne
    {
        return $this->hasOne(Pesage::class, 'collecte_id');
    }

    /**
     * Accès direct à l'utilisateur du client (pratique)
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Client::class,
            'id',
            'user_id',
            'planification_id',
            'id'
        );
    }
}

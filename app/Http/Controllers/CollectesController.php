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

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'heure_depart' => 'datetime',
        'heure_fin' => 'datetime',
    ];

    // ==================== RELATIONS ====================

    public function planification(): BelongsTo
    {
        return $this->belongsTo(Planification::class);
    }

    /**
     * Une collecte a UN SEUL pesage
     */
    public function pesage(): HasOne
    {
        return $this->hasOne(Pesage::class, 'id_collecte'); // ← Correction ici
    }

    /**
     * Accès direct au client
     */
    public function client()
    {
        return $this->through('planification')
            ->through('abonnement')
            ->hasOne(Client::class);
    }
}

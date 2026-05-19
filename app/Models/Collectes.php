<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    // ==================== RELATIONS ====================

    public function planification(): BelongsTo
    {
        return $this->belongsTo(Planification::class);
    }

    public function pesage(): HasOne
    {
        return $this->hasOne(Pesage::class, 'id_collecte');
    }

    /**
     * Relation pour la vue détails (show) qui attend un ensemble de pesages
     */
    public function pesages(): HasMany
    {
        return $this->hasMany(Pesage::class, 'id_collecte');
    }

    /**
     * Accès direct au client via Planification → Abonnement
     */
    public function getClientAttribute()
    {
        return $this->planification?->abonnement?->client;
    }

    /**
     * Accès direct à l'utilisateur du client (très pratique dans les vues)
     */
    public function getUserAttribute()
    {
        return $this->client?->user;
    }
}

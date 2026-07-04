<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Collecte extends Model
{
    protected $table = 'collectes';

    protected $fillable = [
        'planification_id',
        'photo',
        'commentaire',
        'statut',
        'heure_depart',
        'heure_fin',
        // Ajoute d'autres champs si nécessaire (poids_total, etc.)
    ];

    protected $casts = [
        'heure_depart' => 'datetime',
        'heure_fin'    => 'datetime',
    ];

    // ====================== RELATIONS ======================
    public function planification(): BelongsTo
    {
        return $this->belongsTo(Planification::class);
    }

    public function user(): BelongsTo   // Le collecteur qui a réalisé la collecte
    {
        return $this->belongsTo(User::class, 'collecteur_id'); // si tu as cette colonne
    }

    public function pesage(): HasOne
    {
        return $this->hasOne(Pesage::class, 'id_collecte');
    }

    public function pesages(): HasMany
    {
        return $this->hasMany(Pesage::class, 'id_collecte');
    }

    // ====================== ACCESSORS ======================
    public function getClientAttribute()
    {
        return $this->planification?->abonnement?->user;
    }

    public function getUserAttribute()
    {
        return $this->planification?->abonnement?->user;
    }
}
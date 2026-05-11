<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Declaration extends Model
{
    protected $table = 'declarations';

    protected $fillable = [
        'type_dechet',
        'poids_estime',
        'photo',
        'description',
        'statut',
        'user_id',
        'abonnement_id',
    ];

    protected $casts = [
        'statut' => 'string',
    ];

    public const STATUTS = [
        'en_attente' => 'En attente',
        'valide' => 'Validée',
        'rejete' => 'Rejetée',
        'brouillon' => 'Brouillon',
        // plus tard : 'planifiee', etc.
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function abonnement(): BelongsTo
    {
        return $this->belongsTo(Abonnement::class);
    }

    public function planification(): HasOne
    {
        return $this->hasOne(Planification::class);
    }
}

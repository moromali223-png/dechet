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
        'poids_estime' => 'decimal:2',
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

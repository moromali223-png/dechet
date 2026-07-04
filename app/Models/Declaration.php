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
        'planification_id',   // ajouté pour cohérence
    ];

    protected $casts = [
        'poids_estime' => 'decimal:2',
        'statut' => 'string',
    ];

    public const STATUTS = [
        'en_attente' => 'En attente',
        'valide' => 'Validée',
        'rejete' => 'Rejetée',
        'brouillon' => 'Brouillon',
        'planifiee' => 'Planifiée',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function abonnement(): BelongsTo
    {
        return $this->belongsTo(Abonnement::class);
    }

    public function planification(): BelongsTo
    {
        return $this->belongsTo(Planification::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeRejete($query)
    {
        return $query->where('statut', 'rejete');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getStatutFormateAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesage extends Model
{
    use HasFactory;

    protected $table = 'pesage';

    protected $fillable = [
        'id_collecte',
        'agent_id',           // ← ajouté
        'poids',
        'unite',
        'description',
        'statut',
        'date_pesage',
    ];

    protected $casts = [
        'poids' => 'decimal:2',
        'date_pesage' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Un pesage appartient à une collecte
     */
    public function collecte(): BelongsTo
    {
        return $this->belongsTo(Collecte::class, 'id_collecte');
    }

    /**
     * Le pesage est réalisé par un agent (User avec rôle agent)
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id')
                    ->where('role', 'agent');
    }

    /**
     * Relation avec les tries (tri des déchets)
     */
    public function tries(): HasMany
    {
        return $this->hasMany(Trie::class, 'pesage_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeByAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getPoidsFormateAttribute(): string
    {
        return number_format($this->poids ?? 0, 2) . ' ' . ($this->unite ?? 'kg');
    }
}
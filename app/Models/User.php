<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'statut',
        'role',
        'address',
        'zone_id',           // ← ajouté
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function getNomAttribute(): string
    {
        return $this->name;
    }

    public function getUserAttribute(): self
    {
        return $this;
    }
    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function abonnements(): HasMany
    {
        return $this->hasMany(Abonnement::class);
    }

    public function declarations(): HasMany
    {
        return $this->hasMany(Declaration::class);
    }

    public function agentPlanifications(): HasMany
    {
        return $this->hasMany(Planification::class, 'agent_id');
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(Mouvement::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeClients($query)
    {
        return $query->where('role', 'client');
    }

    public function scopeAgents($query)
    {
        return $query->where('role', 'agent');
    }

    public function scopeCollecteurs($query)
    {
        return $query->where('role', 'collecteur');
    }

    public function scopeAdministrateurs($query)
    {
        return $query->where('role', 'administrateur');
    }
}
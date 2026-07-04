<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'ville',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function planifications(): HasMany
    {
        return $this->hasMany(Planification::class);
    }

    // Remplacé Collecteur par User + filtre par rôle
    public function collecteurs(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'collecteur');
    }

    // Relations ajoutées pour cohérence
    public function clients(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'client');
    }

    public function agents(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'agent');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithStats($query)
    {
        return $query->withCount([
            'clients',
            'agents',
            'collecteurs'
        ]);
    }
}
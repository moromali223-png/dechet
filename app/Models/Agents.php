<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agents extends User
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope('role', function (Builder $query) {
            $query->where('role', 'agent');
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function getUserAttribute(): self
    {
        return $this;
    }

    public function getUserIdAttribute(): int
    {
        return $this->id;
    }
}

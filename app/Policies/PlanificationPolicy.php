<?php

namespace App\Policies;

use App\Models\Planification;
use App\Models\User;

class PlanificationPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'agent', 'collecteur'], true);
    }

    public function view(User $user, Planification $planification): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'agent') {
            return $planification->agent_id === $user->id;
        }

        if ($user->role === 'collecteur' && $planification->collecteur) {
            return $planification->collecteur->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Planification $planification): bool
    {
        return $user->role === 'admin'
            || ($user->role === 'agent' && $planification->agent_id === $user->id)
            || ($user->role === 'collecteur' && optional($planification->collecteur)->user_id === $user->id);
    }

    public function delete(User $user, Planification $planification): bool
    {
        return $user->role === 'admin';
    }

    public function restore(User $user, Planification $planification): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Planification $planification): bool
    {
        return $user->role === 'admin';
    }
}

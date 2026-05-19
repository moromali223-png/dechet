<?php

namespace App\Policies;

use App\Models\Declaration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeclarationPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['client', 'admin']);
    }

    public function view(User $user, Declaration $declaration): Response
    {
        return ($user->id === $declaration->user_id || $user->role === 'admin')
            ? Response::allow()
            : Response::deny('Vous ne pouvez pas voir cette déclaration.');
    }

    public function create(User $user): bool
    {
        return $user->role === 'client';
    }

    public function update(User $user, Declaration $declaration): Response
    {
        if ($user->role === 'admin') {
            return Response::allow();
        }

        if ($user->id !== $declaration->user_id) {
            return Response::deny('Vous ne pouvez pas modifier cette déclaration.');
        }

        return $declaration->statut === 'en_attente'
            ? Response::allow()
            : Response::deny('Cette déclaration ne peut plus être modifiée.');
    }

    public function delete(User $user, Declaration $declaration): Response
    {
        if ($user->role === 'admin') {
            return Response::allow();
        }

        if ($user->id !== $declaration->user_id) {
            return Response::deny('Vous ne pouvez pas supprimer cette déclaration.');
        }

        return $declaration->statut === 'en_attente'
            ? Response::allow()
            : Response::deny('Cette déclaration ne peut pas être supprimée.');
    }
}

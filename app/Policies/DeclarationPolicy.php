<?php

namespace App\Policies;

use App\Models\Declaration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeclarationPolicy
{
    public function viewAny(User $user): bool
    {
        // Only authenticated users should be able to view their declarations.
        return $user !== null;
    }

    public function view(User $user, Declaration $declaration): Response
    {
        return $user->id === $declaration->user_id
            ? Response::allow()
            : Response::deny('Vous ne pouvez pas voir cette déclaration.');
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Declaration $declaration): Response
    {
        if ($user->id !== $declaration->user_id) {
            return Response::deny('Vous ne pouvez pas modifier cette déclaration.');
        }

        return $declaration->statut === 'en_attente'
            ? Response::allow()
            : Response::deny('Cette déclaration ne peut plus être modifiée.');
    }

    public function delete(User $user, Declaration $declaration): Response
    {
        if ($user->id !== $declaration->user_id) {
            return Response::deny('Vous ne pouvez pas supprimer cette déclaration.');
        }

        return $declaration->statut === 'en_attente'
            ? Response::allow()
            : Response::deny('Cette déclaration ne peut pas être supprimée.');
    }
}

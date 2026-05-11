<?php

namespace App\Policies;

use App\Models\Abonnement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AbonnementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Abonnement $abonnement): Response
    {
        // L'utilisateur peut voir ses propres abonnements
        if ($user->id === $abonnement->user_id) {
            return Response::allow();
        }

        // Les administrateurs peuvent voir tous les abonnements
        if ($user->role === 'admin') {
            return Response::allow();
        }

        return Response::deny('Vous n\'êtes pas autorisé à voir cet abonnement.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Abonnement $abonnement): Response
    {
        // L'utilisateur peut modifier ses propres abonnements
        if ($user->id === $abonnement->user_id) {
            return Response::allow();
        }

        // Les administrateurs peuvent modifier tous les abonnements
        if ($user->role === 'admin') {
            return Response::allow();
        }

        return Response::deny('Vous n\'êtes pas autorisé à modifier cet abonnement.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Abonnement $abonnement): Response
    {
        // L'utilisateur peut supprimer ses propres abonnements
        if ($user->id === $abonnement->user_id) {
            return Response::allow();
        }

        // Les administrateurs peuvent supprimer tous les abonnements
        if ($user->role === 'admin') {
            return Response::allow();
        }

        return Response::deny('Vous n\'êtes pas autorisé à supprimer cet abonnement.');
    }

    /**
     * Determine whether the user can activate the abonnement.
     */
    public function activer(User $user, Abonnement $abonnement): Response
    {
        // Seuls les administrateurs peuvent activer les abonnements
        if ($user->role !== 'admin') {
            return Response::deny('Seuls les administrateurs peuvent activer les abonnements.');
        }

        // L'abonnement doit être en attente
        if ($abonnement->statut !== 'en_attente') {
            return Response::deny('Cet abonnement ne peut pas être activé.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can reject the abonnement.
     */
    public function rejeter(User $user, Abonnement $abonnement): Response
    {
        // Seuls les administrateurs peuvent rejeter les abonnements
        if ($user->role !== 'admin') {
            return Response::deny('Seuls les administrateurs peuvent rejeter les abonnements.');
        }

        // L'abonnement doit être en attente
        if ($abonnement->statut !== 'en_attente') {
            return Response::deny('Cet abonnement ne peut pas être rejeté.');
        }

        return Response::allow();
    }
}

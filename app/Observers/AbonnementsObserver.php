<?php

namespace App\Observers;

use App\Models\Abonnement;
use App\Models\Paiement;

class AbonnementsObserver
{
    /**
     * Après création d'un abonnement
     */
    public function created(Abonnement $abonnement): void
    {
        // Création automatique du paiement
        Paiement::create([
            'type_paiement'      => 'abonnement',
            'abonnement_id'      => $abonnement->id,
            'commande_id'        => null,
            'mode_paiement'      => 'mobile_money',
            'montant'            => $abonnement->montant ?? 0,
            'statut'             => 'valide',
            'reference_paiement' => 'ABO-' . strtoupper(uniqid()),
        ]);

        // Génération de la première planification
        if ($abonnement->statut === 'actif') {
            $abonnement->generateNextPlanification();
        }
    }

    /**
     * Après modification d'un abonnement
     */
    public function updated(Abonnement $abonnement): void
    {
        // Activation de l'abonnement
        if (
            $abonnement->wasChanged('statut') &&
            $abonnement->statut === 'actif'
        ) {
            $abonnement->generateNextPlanification();
        }
    }
}
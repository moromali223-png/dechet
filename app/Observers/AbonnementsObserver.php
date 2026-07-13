<?php

namespace App\Observers;

use App\Models\Abonnement;
use App\Models\Paiement;
use App\Services\PlanificationService;
use Illuminate\Support\Facades\Log;

class AbonnementsObserver
{
    protected PlanificationService $planificationService;

    public function __construct(PlanificationService $planificationService)
    {
        $this->planificationService = $planificationService;
    }

    /**
     * Après la création d'un abonnement
     */
    public function created(Abonnement $abonnement): void
    {
        try {
            // Création automatique du paiement initial
            Paiement::create([
                'type_paiement'      => 'abonnement',
                'abonnement_id'      => $abonnement->id,
                'commande_id'        => null,
                'mode_paiement'      => 'mobile_money',
                'montant'            => $abonnement->montant ?? 0,
                'statut'             => $abonnement->statut === 'actif' ? 'valide' : 'en_attente',
                'reference_paiement' => 'ABO-' . strtoupper(uniqid()),
                'date_paiement'      => now(),
            ]);

            // Génération de la première planification seulement si l'abonnement est actif
            if ($abonnement->statut === 'actif') {
                $this->planificationService->createNextPlanification($abonnement);
            }
        } catch (\Exception $e) {
            Log::error('Erreur dans AbonnementsObserver@created : ' . $e->getMessage(), [
                'abonnement_id' => $abonnement->id
            ]);
        }
    }

    /**
     * Après modification d'un abonnement
     */
    public function updated(Abonnement $abonnement): void
    {
        try {
            // Activation de l'abonnement (ex: passage de 'en_attente' → 'actif')
            if ($abonnement->wasChanged('statut') && $abonnement->statut === 'actif') {
                // Supprimer les anciennes planifications si nécessaire
                $abonnement->planifications()
                    ->where('date_prevue', '>=', now())
                    ->delete();

                $this->planificationService->createNextPlanification($abonnement);
            }

            // Si la fréquence ou le jour de collecte a changé → régénérer les planifications futures
            if ($abonnement->wasChanged(['frequence', 'jour_collecte', 'date_debut'])) {
                $abonnement->planifications()
                    ->where('date_prevue', '>=', now())
                    ->delete();

                $this->planificationService->createNextPlanification($abonnement);
            }
        } catch (\Exception $e) {
            Log::error('Erreur dans AbonnementsObserver@updated : ' . $e->getMessage(), [
                'abonnement_id' => $abonnement->id
            ]);
        }
    }

    /**
     * Avant suppression (optionnel mais utile)
     */
    public function deleting(Abonnement $abonnement): void
    {
        // Supprimer les planifications et paiements liés
        $abonnement->planifications()->delete();
        $abonnement->paiements()->delete(); // si tu as une relation paiements()
    }
}
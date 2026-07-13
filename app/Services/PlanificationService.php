<?php

namespace App\Services;

use App\Models\Abonnement;
use App\Models\Declaration;
use App\Models\Planification;
use Carbon\Carbon;

class PlanificationService
{
    /**
     * Vérifie si la prochaine planification doit être générée
     */
    public function shouldGenerateNextPlanification(Abonnement $abonnement): bool
    {
        // Seulement les abonnements actifs
        if ($abonnement->statut !== 'actif') {
            return false;
        }


        // Récupérer la dernière planification
        $lastPlanif = $abonnement->planifications()
            ->orderByDesc('date_prevue')
            ->first();


        // Aucun historique => première génération
        if (!$lastPlanif) {
            return true;
        }


        /*
        Exemple :

        Dernière collecte :
        13/07/2026

        Le 13/07 :
        false

        Le 14/07 à 01h :
        true
        */

        return now()->startOfDay()
            ->greaterThan(
                Carbon::parse($lastPlanif->date_prevue)
                    ->startOfDay()
            );
    }



    /**
     * Création de la prochaine planification uniquement
     */
    public function createNextPlanification(Abonnement $abonnement): void
    {

        /*
        Protection :
        Si une planification future existe déjà,
        on ne crée rien.
        */

        $futureExists = $abonnement->planifications()
            ->whereDate('date_prevue', '>=', today())
            ->exists();


        if ($futureExists) {
            return;
        }



        // Dernière planification existante
        $lastPlanif = $abonnement->planifications()
            ->orderByDesc('date_prevue')
            ->first();



        // Calcul de la prochaine date
        $nextDate = $lastPlanif
            ? $this->calculateNextDate(
                $lastPlanif->date_prevue,
                $abonnement
            )
            : $this->calculateFirstPlanificationDate($abonnement);



        // Respect de la date de fin abonnement
        if (
            $abonnement->date_fin &&
            $nextDate->greaterThan(
                Carbon::parse($abonnement->date_fin)
            )
        ) {
            return;
        }



        // Sécurité anti doublon
        if (
            $abonnement->planifications()
            ->whereDate('date_prevue', $nextDate->toDateString())
            ->exists()
        ) {
            return;
        }



        // Création déclaration liée
        $declaration = $this->createDeclaration($abonnement);



        // Création planification
        Planification::create([

            'code_planification' =>
                'ABN-' .
                $abonnement->id .
                '-' .
                $nextDate->format('Ymd'),


            'nom_tournee' =>
                'Collecte abonnement #' . $abonnement->id,


            'date_prevue' =>
                $nextDate->toDateString(),


            'jour_semaine' =>
                ucfirst(
                    $nextDate
                    ->locale('fr')
                    ->dayName
                ),


            'periode' =>
                strtoupper($abonnement->frequence),


            'type_collecte' =>
                'SYSTEMATIQUE',


            'statut' =>
                'planifiee',


            'zone_id' =>
                $abonnement->user->zone_id ?? 1,


            'abonnement_id' =>
                $abonnement->id,


            'declaration_id' =>
                $declaration->id,


            'priorite' =>
                1,

        ]);
    }





    /**
     * Création déclaration automatique
     */
    private function createDeclaration(Abonnement $abonnement): Declaration
    {
        return $abonnement->declarations()->create([

            'user_id' =>
                $abonnement->user_id,


            'abonnement_id' =>
                $abonnement->id,


            'type_dechet' =>
                $abonnement->type_dechet,


            'poids_estime' =>
                $abonnement->poids_estime,


            'description' =>
                'Déclaration générée automatiquement depuis l’abonnement.',


            'statut' =>
                'planifiee',

        ]);
    }





    /**
     * Première date de collecte
     */
 private function calculateFirstPlanificationDate(
    Abonnement $abonnement
): Carbon {

    $date = Carbon::parse($abonnement->date_debut);

    // =========================
    // HEBDOMADAIRE
    // =========================
    if ($abonnement->frequence === 'hebdomadaire') {

        $jour = $this->normalizeEnglishWeekDay(
            $abonnement->jour_collecte
        );

        while ($date->format('l') !== $jour) {
            $date->addDay();
        }

        return $date;
    }

    // =========================
    // MENSUELLE
    // =========================
    if ($abonnement->frequence === 'mensuelle') {

        $jour = (int) $abonnement->jour_collecte;

        $date->day(
            min($jour, $date->daysInMonth)
        );

        // si la date est déjà passée
        if ($date->lt(Carbon::parse($abonnement->date_debut))) {

            $date->addMonthNoOverflow();

            $date->day(
                min($jour, $date->daysInMonth)
            );
        }

        return $date;
    }

    return $date;
}



    /**
     * Calcul prochaine date
     */
    private function calculateNextDate(
    string $lastDate,
    Abonnement $abonnement
): Carbon {

    $date = Carbon::parse($lastDate);

    // =========================
    // HEBDOMADAIRE
    // =========================
    if ($abonnement->frequence === 'hebdomadaire') {

        return $date->addWeek();

    }

    // =========================
    // MENSUELLE
    // =========================
    if ($abonnement->frequence === 'mensuelle') {

        $jour = (int) $abonnement->jour_collecte;

        $date->addMonthNoOverflow();

        $date->day(
            min($jour, $date->daysInMonth)
        );

        return $date;
    }

    return $date;
}





    private function normalizeEnglishWeekDay(?string $day): string
    {
        return match (
            mb_strtolower(trim($day ?? ''))
        ) {

            'lundi' =>
                'Monday',

            'mardi' =>
                'Tuesday',

            'mercredi' =>
                'Wednesday',

            'jeudi' =>
                'Thursday',

            'vendredi' =>
                'Friday',

            'samedi' =>
                'Saturday',

            'dimanche' =>
                'Sunday',

            default =>
                'Monday',
        };
    }





    /**
     * Utilisé par Abonnement.php
     */
    public function calculateFirstPlanificationDateForDisplay(
        Abonnement $abonnement
    ): Carbon {

        return $this->calculateFirstPlanificationDate($abonnement);

    }



    public function calculateNextDateForDisplay(
        string $lastDate,
        Abonnement $abonnement
    ): Carbon {

        return $this->calculateNextDate(
            $lastDate,
            $abonnement
        );

    }

}
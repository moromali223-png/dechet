<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Abonnement extends Model
{
    public const STATUSES = [
        'actif',
        'expire',
        'annule',
        'en_attente',
        'rejete',
    ];

    public const FREQUENCIES = [
        'hebdomadaire',
        'mensuelle',
    ];

    public const WEEK_DAYS = [
        'lundi',
        'mardi',
        'mercredi',
        'jeudi',
        'vendredi',
        'samedi',
        'dimanche',
    ];

    protected $fillable = [
        'user_id',
        'type_abonnement',
        'type_dechet',
        'frequence',
        'jour_collecte',
        'poids_estime',
        'montant',
        'date_debut',
        'date_fin',
        'statut',
        'motif_rejet',
        'date_activation',
        'date_rejet',
        // Champs d'adresse
        'rue',
        'quartier',
        'porte',
        'repere',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_activation' => 'datetime',
        'date_rejet' => 'datetime',
        'poids_estime' => 'decimal:2',
        'montant' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'user_id', 'user_id');
    }
       public function declarations(): HasMany
    {
        return $this->hasMany(Declaration::class);
    }

    public function planifications(): HasMany
    {
        return $this->hasMany(Planification::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('statut', 'actif')
            ->whereDate('date_debut', '<=', now())
            ->whereDate('date_fin', '>=', now());
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    public function getAdresseCompleteAttribute(): string
    {
        $parts = array_filter([
            $this->rue,
            $this->quartier,
            $this->porte,
        ]);

        return implode(', ', $parts) ?: 'Adresse non renseignée';
    }

    public function getAdresseAvecRepereAttribute(): string
    {
        $adresse = $this->adresse_complete;

        if ($this->repere) {
            $adresse .= ' (Références: '.$this->repere.')';
        }

        return $adresse;
    }

    /*
    |--------------------------------------------------------------------------
    | Génération professionnelle d'une seule planification active
    |--------------------------------------------------------------------------
    */

    public function generateNextPlanification(): void
    {
        if ($this->statut !== 'actif') {
            return;
        }

        if (! $this->date_debut) {
            return;
        }

        if (! $this->client || ! $this->client->zone_id) {
            return;
        }

        // Une seule planification active à la fois
        $activeStatuses = [
            'planifiee',
            'assignee',
            'en_cours',
        ];

        if ($this->planifications()
            ->whereIn('statut', $activeStatuses)
            ->exists()) {
            return;
        }

        // Dernière planification existante
        $lastPlanification = $this->planifications()
            ->latest('date_prevue')
            ->first();

        // Première planification
        if (! $lastPlanification) {
            $nextDate = $this->calculateFirstPlanificationDate();
        } else {
            $nextDate = $this->calculateNextDate(
                Carbon::parse($lastPlanification->date_prevue)
            );
        }

        // Ne pas dépasser la date de fin
        if (
            $this->date_fin &&
            $nextDate->gt(Carbon::parse($this->date_fin))
        ) {
            return;
        }

        $this->createPlanification(
            $nextDate,
            $this->client->zone_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Calcul de la première date
    |--------------------------------------------------------------------------
    */

    private function calculateFirstPlanificationDate(): Carbon
    {
        $date = Carbon::parse($this->date_debut);

        if ($this->frequence === 'hebdomadaire') {
            $englishDay = $this->normalizeEnglishWeekDay(
                $this->jour_collecte
            );

            while ($date->format('l') !== $englishDay) {
                $date->addDay();
            }
        }

        if ($this->frequence === 'mensuelle') {
            $day = (int) $this->jour_collecte;
            $date->day($day);

            if ($date->lt(Carbon::parse($this->date_debut))) {
                $date->addMonthNoOverflow();
            }
        }

        return $date;
    }

    /*
    |--------------------------------------------------------------------------
    | Calcul de la prochaine date
    |--------------------------------------------------------------------------
    */

    private function calculateNextDate(Carbon $date): Carbon
    {
        return match ($this->frequence) {
            'hebdomadaire' => $date->addWeek(),
            'mensuelle' => $date->addMonthNoOverflow(),
            default => $date->addWeek(),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Création de la planification
    |--------------------------------------------------------------------------
    */

    private function createPlanification(
        Carbon $date,
        int $zoneId
    ): void {
        $this->planifications()->create([
            'code_planification' => 'ABN-'.$this->id.'-'.$date->format('YmdHis'),
            'nom_tournee' => 'Collecte abonnement #'.$this->id,
            'date_prevue' => $date->toDateString(),
            'jour_semaine' => ucfirst(
                $date->locale('fr')->dayName
            ),
            'periode' => ucfirst($this->frequence),
            'type_collecte' => 'SYSTEMATIQUE',
            'statut' => 'planifiee',
            'zone_id' => $zoneId,
            'priorite' => 1,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function normalizeEnglishWeekDay(string $day): string
    {
        return match (mb_strtolower(trim($day))) {
            'lundi' => 'Monday',
            'mardi' => 'Tuesday',
            'mercredi' => 'Wednesday',
            'jeudi' => 'Thursday',
            'vendredi' => 'Friday',
            'samedi' => 'Saturday',
            'dimanche' => 'Sunday',
            default => ucfirst(strtolower($day)),
        };
    }
}

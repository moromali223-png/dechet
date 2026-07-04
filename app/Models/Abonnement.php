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
        'actif', 'expire', 'annule', 'en_attente', 'rejete',
    ];

    public const FREQUENCIES = [
        'hebdomadaire', 'mensuelle',
    ];

    public const WEEK_DAYS = [
        'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche',
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
        'rue', 'quartier', 'porte', 'repere',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_activation' => 'datetime',
        'date_rejet' => 'datetime',
        'poids_estime' => 'decimal:2',
        'montant' => 'decimal:2',
    ];

    // ====================== RELATIONS ======================
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function declarations(): HasMany
    {
        return $this->hasMany(Declaration::class);
    }

    public function planifications(): HasMany
    {
        return $this->hasMany(Planification::class);
    }

    // ====================== SCOPES ======================
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('statut', 'actif')
            ->whereDate('date_debut', '<=', now())
            ->whereDate('date_fin', '>=', now());
    }

    // ====================== ACCESSORS ======================
    public function getAdresseCompleteAttribute(): string
    {
        $parts = array_filter([$this->rue, $this->quartier, $this->porte]);
        return implode(', ', $parts) ?: 'Adresse non renseignée';
    }

    public function getAdresseAvecRepereAttribute(): string
    {
        $adresse = $this->adresse_complete;
        if ($this->repere) {
            $adresse .= ' (Références: ' . $this->repere . ')';
        }
        return $adresse;
    }

    // ====================== GÉNÉRATION AUTOMATIQUE ======================
    public function generateNextPlanification(): void
    {
        if ($this->statut !== 'actif' || !$this->date_debut) {
            return;
        }

        if (!$this->user || $this->user->role !== 'client' || !$this->user->zone_id) {
            return;
        }

        // Une seule planification active à la fois
        if ($this->planifications()
            ->whereIn('statut', ['planifiee', 'assignee', 'en_cours'])
            ->exists()) {
            return;
        }

        $lastPlanification = $this->planifications()->latest('date_prevue')->first();

        $nextDate = $lastPlanification
            ? $this->calculateNextDate(Carbon::parse($lastPlanification->date_prevue))
            : $this->calculateFirstPlanificationDate();

        if ($this->date_fin && $nextDate->gt(Carbon::parse($this->date_fin))) {
            return;
        }

        $this->createPlanification($nextDate, $this->user->zone_id);
    }

    private function createDeclaration(): Declaration
    {
        return $this->declarations()->create([
            'user_id'       => $this->user_id,
            'abonnement_id' => $this->id,
            'type_dechet'   => $this->type_dechet,
            'poids_estime'  => $this->poids_estime,
            'description'   => 'Déclaration générée automatiquement depuis l’abonnement.',
            'statut'        => 'planifiee',
        ]);
    }

    private function createPlanification(Carbon $date, int $zoneId): void
    {
        $declaration = $this->createDeclaration();

        $this->planifications()->create([
            'code_planification' => 'ABN-' . $this->id . '-' . $date->format('YmdHis'),
            'nom_tournee'        => 'Collecte abonnement #' . $this->id,
            'date_prevue'        => $date->toDateString(),
            'jour_semaine'       => ucfirst($date->locale('fr')->dayName),
            'periode'            => ucfirst($this->frequence),
            'type_collecte'      => 'SYSTEMATIQUE',
            'statut'             => 'planifiee',
            'zone_id'            => $zoneId,
            'abonnement_id'      => $this->id,
            'declaration_id'     => $declaration->id,
            'priorite'           => 1,
        ]);
    }

    // ====================== CALCUL DES DATES ======================
    private function calculateFirstPlanificationDate(): Carbon
    {
        $date = Carbon::parse($this->date_debut);

        if ($this->frequence === 'hebdomadaire') {
            $englishDay = $this->normalizeEnglishWeekDay($this->jour_collecte);

            while ($date->format('l') !== $englishDay) {
                $date->addDay();
            }
        }

        if ($this->frequence === 'mensuelle') {
            $day = (int) $this->jour_collecte;
            $date->day($day);

            // Si le jour est déjà passé ce mois-ci, on passe au mois suivant
            if ($date->lt(Carbon::parse($this->date_debut))) {
                $date->addMonthNoOverflow();
            }
        }

        return $date;
    }

    private function calculateNextDate(Carbon $date): Carbon
    {
        return match ($this->frequence) {
            'hebdomadaire' => $date->addWeek(),
            'mensuelle'    => $date->addMonthNoOverflow(),
            default        => $date->addWeek(),
        };
    }

    private function normalizeEnglishWeekDay(string $day): string
    {
        return match (mb_strtolower(trim($day))) {
            'lundi'     => 'Monday',
            'mardi'     => 'Tuesday',
            'mercredi'  => 'Wednesday',
            'jeudi'     => 'Thursday',
            'vendredi'  => 'Friday',
            'samedi'    => 'Saturday',
            'dimanche'  => 'Sunday',
            default     => ucfirst(strtolower($day)),
        };
    }
}
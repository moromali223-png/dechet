<?php

namespace App\Models;

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
        'hebdomadaire' => 'Hebdomadaire',
        'mensuelle'    => 'Mensuelle',
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
        'rue', 
        'quartier', 
        'porte', 
        'repere',
    ];

    protected $casts = [
        'date_debut'       => 'date',
        'date_fin'         => 'date',
        'date_activation'  => 'datetime',
        'date_rejet'       => 'datetime',
        'poids_estime'     => 'decimal:2',
        'montant'          => 'decimal:2',
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

    // ====================== HELPERS ======================

    /**
     * Vérifie si l'abonnement est toujours valide
     */
    public function isValid(): bool
    {
        return $this->statut === 'actif' 
            && $this->date_fin 
            && $this->date_fin->isFutureOrToday();
    }

    /**
     * Retourne la dernière planification
     */
    public function getLastPlanificationAttribute()
    {
        return $this->planifications()->latest('date_prevue')->first();
    }

    /**
     * Retourne la prochaine date théorique de collecte
     */
    public function getNextTheoreticalDateAttribute()
    {
        $last = $this->last_planification;

        if (!$last) {
            return app(\App\Services\PlanificationService::class)
                ->calculateFirstPlanificationDateForDisplay($this);
        }

        return app(\App\Services\PlanificationService::class)
            ->calculateNextDateForDisplay($last->date_prevue, $this);
    }

    // ====================== MÉTHODES POUR FRÉQUENCE ======================

    public function isHebdomadaire(): bool
    {
        return $this->frequence === 'hebdomadaire';
    }

    public function isMensuelle(): bool
    {
        return $this->frequence === 'mensuelle';
    }

    /**
     * Affichage du jour de collecte selon la fréquence
     */
    public function getJourCollecteDisplayAttribute(): string
    {
        if ($this->isMensuelle() && is_numeric($this->jour_collecte)) {
            return $this->jour_collecte . ' du mois';
        }

        return ucfirst($this->jour_collecte ?? 'Non défini');
    }
}
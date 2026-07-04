<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

use App\Models\Collecte;   // ← Cet import est OBLIGATOIRE

class Planification extends Model
{
    protected $table = 'planifications';

    protected $fillable = [
        'code_planification', 'nom_tournee', 'jour_semaine', 'date_prevue',
        'periode', 'type_collecte', 'statut', 'zone_id', 'collecteur_id',
        'declaration_id', 'abonnement_id', 'agent_id', 'ordre_passage',
        'duree_estimee', 'priorite', 'heure_depart', 'heure_arrivee', 'heure_fin',
    ];

    protected $casts = [
        'date_prevue' => 'date',
        'ordre_passage' => 'integer',
        'duree_estimee' => 'integer',
        'priorite' => 'integer',
        'heure_depart' => 'datetime',
        'heure_arrivee' => 'datetime',
        'heure_fin' => 'datetime',
    ];

    public const STATUSES = [
        'brouillon', 'planifiee', 'assignee', 'en_route', 'en_cours',
        'terminee', 'annulee', 'reportee',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function collecteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collecteur_id')
                    ->where('role', 'collecteur');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id')
                    ->where('role', 'agent');
    }

    public function declaration(): BelongsTo
    {
        return $this->belongsTo(Declaration::class);
    }

    public function abonnement(): BelongsTo
    {
        return $this->belongsTo(Abonnement::class);
    }

    public function collecte(): HasOne
    {
        return $this->hasOne(Collecte::class);
    }

    public function getClientAttribute()
    {
        return $this->abonnement?->user;
    }

    public function getClientIdAttribute()
    {
        return $this->abonnement?->user_id;
    }

    public function scopeForToday($query)
    {
        return $query->whereDate('date_prevue', today());
    }

    public function scopeActive($query)
    {
        return $query->whereIn('statut', ['planifiee', 'assignee', 'en_route', 'en_cours']);
    }

    public function scopeForCollecteur($query, $collecteurId)
    {
        return $query->where('collecteur_id', $collecteurId);
    }

    public function isActive(): bool
    {
        return in_array($this->statut, ['planifiee', 'assignee', 'en_route', 'en_cours'], true);
    }

    // =========================================================================
    // Factory : crée une planification brouillon depuis une déclaration validée
    // =========================================================================
    public static function createFromDeclaration(Declaration $declaration): static
    {
        // Charger les relations nécessaires
        $declaration->loadMissing(['user.zone', 'abonnement']);

        $user        = $declaration->user;
        $abonnement  = $declaration->abonnement;

        // Déterminer la zone : celle de l'abonnement ou celle du user
        $zoneId = $abonnement?->user?->zone_id
                ?? $user?->zone_id
                ?? null;

        // Code planification unique
        $code = 'PLN-DECL-' . $declaration->id . '-' . strtoupper(Str::random(5));

        // Date proposée : dans 3 jours ouvrables par défaut
        $datePrevue = now()->addDays(3)->format('Y-m-d');

        return static::create([
            'code_planification' => $code,
            'nom_tournee'        => 'Collecte déclaration #' . $declaration->id,
            'jour_semaine'       => ucfirst(now()->addDays(3)->locale('fr')->dayName),
            'date_prevue'        => $datePrevue,
            'periode'            => 'HEBDOMADAIRE',
            'type_collecte'      => 'MIXTE',
            'statut'             => 'brouillon',
            'priorite'           => 2,
            'zone_id'            => $zoneId ?? Zone::first()?->id ?? 1,
            'declaration_id'     => $declaration->id,
            'abonnement_id'      => $declaration->abonnement_id,
        ]);
    }
}
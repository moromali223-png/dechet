<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Planification extends Model
{
    protected $table = 'planifications';

    protected $fillable = [
        'code_planification',
        'nom_tournee',
        'jour_semaine',
        'date_prevue',
        'periode',
        'type_collecte',
        'statut',
        'zone_id',
        'collecteur_id',
        'declaration_id',
        'abonnement_id',
        'agent_id',
        'ordre_passage',
        'duree_estimee',
        'priorite',
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
        'brouillon',
        'planifiee',
        'assignee',
        'en_route',
        'en_cours',
        'terminee',
        'annulee',
        'reportee',
    ];

    // ==================== RELATIONS ====================

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function collecteur(): BelongsTo
    {
        return $this->belongsTo(Collecteur::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
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
        return $this->hasOne(Collectes::class);
    }

    public function getClientAttribute()
    {
        return $this->abonnement?->client;
    }

    // ==================== SCOPES ====================

    public function scopeForToday($query)
    {
        return $query->whereDate('date_prevue', now());
    }

    public function scopeActive($query)
    {
        return $query->whereIn('statut', [
            'planifiee',
            'assignee',
            'en_route',
            'en_cours',
        ]);
    }

    // ==================== METHODS ====================

    public function isActive(): bool
    {
        return in_array($this->statut, [
            'planifiee',
            'assignee',
            'en_route',
            'en_cours',
        ], true);
    }

    public static function createFromDeclaration(Declaration $declaration): self
    {
        $datePrevue = Carbon::now()->addDay();

        $zoneId = optional(optional($declaration->user->client)->zone)->id
            ?? Zone::first()?->id;

        return self::create([
            'code_planification' => 'D-'.$declaration->id.'-'.$datePrevue->format('Ymd'),
            'nom_tournee' => 'Tournée déclaration #'.$declaration->id,
            'jour_semaine' => $datePrevue->translatedFormat('l'),
            'date_prevue' => $datePrevue->toDateString(),
            'periode' => 'PONCTUELLE',
            'type_collecte' => $declaration->type_dechet,
            'statut' => 'planifiee',
            'zone_id' => $zoneId,
            'collecteur_id' => null,
            'declaration_id' => $declaration->id,
            'abonnement_id' => $declaration->abonnement_id,
            'agent_id' => null,
            'ordre_passage' => 1,
            'duree_estimee' => 60,
            'priorite' => 2,
        ]);
    }
}

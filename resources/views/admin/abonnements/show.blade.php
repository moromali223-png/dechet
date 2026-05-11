@extends('layouts.app')

@section('title', 'Détails de l\'abonnement')

@section('content')
<div class="container py-4">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">
                Abonnement #{{ $abonnement->id }}
            </h1>
            <p class="text-muted mb-0">
                Détails complets de l'abonnement.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('abonnements.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i>
                Retour
            </a>

            <a href="{{ route('abonnements.edit', $abonnement->id) }}" class="btn btn-warning">
                <i class="bx bx-edit me-1"></i>
                Modifier
            </a>

            @if(auth()->user()->role === 'admin' && $abonnement->statut === 'en_attente')
                <form action="{{ route('abonnements.activer', $abonnement->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success" onclick="return confirm('Êtes-vous sûr d\'activer cet abonnement ?')">
                        <i class="bx bx-check me-1"></i>
                        Activer
                    </button>
                </form>

                <a href="{{ route('abonnements.rejeter.form', $abonnement->id) }}" class="btn btn-danger">
                    <i class="bx bx-x me-1"></i>
                    Rejeter
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Informations client -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bx bx-user me-2"></i>
                        Informations du client
                    </h5>
                </div>

                <div class="card-body">
                    <p class="mb-3">
                        <strong>Nom :</strong><br>
                        {{ $abonnement->user->name ?? 'Non défini' }}
                    </p>

                    <p class="mb-3">
                        <strong>Email :</strong><br>
                        {{ $abonnement->user->email ?? 'Non renseigné' }}
                    </p>

                    <p class="mb-0">
                        <strong>Zone :</strong><br>
                        {{ $abonnement->user->zone->nom ?? 'Non définie' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Informations abonnement -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bx bx-package me-2"></i>
                        Détails de l'abonnement
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="text-muted small">Type d'abonnement</label>
                            <div class="fw-semibold">{{ $abonnement->type_abonnement }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Type de déchet</label>
                            <div class="fw-semibold">{{ $abonnement->type_dechet }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Fréquence</label>
                            <div>{{ ucfirst($abonnement->frequence) }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Jour de collecte</label>
                            <div>{{ ucfirst($abonnement->jour_collecte) }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Poids estimé</label>
                            <div>{{ number_format($abonnement->poids_estime, 2, ',', ' ') }} kg</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Montant</label>
                            <div class="fw-bold text-success">
                                {{ number_format($abonnement->montant, 0, ',', ' ') }} FCFA
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Date de début</label>
                            <div>
                                {{ \Carbon\Carbon::parse($abonnement->date_debut)->format('d/m/Y') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Date de fin</label>
                            <div>
                                {{ \Carbon\Carbon::parse($abonnement->date_fin)->format('d/m/Y') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Statut</label>
                            <div>
                                @php
                                    $badge = match($abonnement->statut) {
                                        'actif' => 'success',
                                        'expire', 'expiré' => 'secondary',
                                        'annule', 'annulé' => 'danger',
                                        default => 'warning text-dark',
                                    };
                                @endphp

                                <span class="badge bg-{{ $badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $abonnement->statut)) }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Créé le</label>
                            <div>
                                {{ $abonnement->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Adresse de collecte -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bx bx-map-pin me-2"></i>
                        Adresse de collecte
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Rue</label>
                            <div class="fw-semibold">{{ $abonnement->rue ?: 'Non renseignée' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Quartier</label>
                            <div class="fw-semibold">{{ $abonnement->quartier ?: 'Non renseigné' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Porte</label>
                            <div class="fw-semibold">{{ $abonnement->porte ?: 'Non renseignée' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Adresse complète</label>
                            <div class="fw-semibold">{{ $abonnement->adresse_complete }}</div>
                        </div>

                        @if($abonnement->repere)
                            <div class="col-12">
                                <label class="text-muted small">Références / Point de repère</label>
                                <div class="fw-semibold text-primary">
                                    <i class="bx bx-info-circle me-1"></i>
                                    {{ $abonnement->repere }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
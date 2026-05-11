@extends('collecteur.layouts.app')

@section('title', 'Détails de la tournée')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center">

            <div>

                <h2 class="fw-bold mb-1">

                    <i class="bx bx-map text-primary me-2"></i>

                    Détails de la tournée

                </h2>

                <p class="text-muted mb-0">

                    Informations complètes sur cette collecte

                </p>

            </div>

            <a href="{{ route('collecteur.tournees') }}"
               class="btn btn-outline-secondary mt-3 mt-md-0">

                ← Retour

            </a>

        </div>

    </div>

    <div class="row g-4">

        {{-- CONTENU PRINCIPAL --}}
        <div class="col-lg-8">

            {{-- CLIENT --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-light">

                    <h5 class="mb-0">
                        👤 Informations client
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Nom du client
                            </label>

                            <div class="fw-bold">

                                {{ optional($planification->abonnement?->client?->user)->name ?? 'N/A' }}

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Email
                            </label>

                            <div>

                                {{ optional($planification->abonnement?->client?->user)->email ?? 'N/A' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- ADRESSE --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-light">

                    <h5 class="mb-0">
                        📍 Adresse de collecte
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Quartier
                            </label>

                            <div class="fw-semibold">

                                {{ $planification->abonnement?->quartier ?? '—' }}

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Rue
                            </label>

                            <div>

                                {{ $planification->abonnement?->rue ?? '—' }}

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Porte
                            </label>

                            <div>

                                {{ $planification->abonnement?->porte ?? '—' }}

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Repère
                            </label>

                            <div>

                                {{ $planification->abonnement?->repere ?? '—' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- DETAILS COLLECTE --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-light">

                    <h5 class="mb-0">
                        ♻️ Informations de collecte
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Type de déchet
                            </label>

                            <div>

                                {{ ucfirst($planification->type_collecte) }}

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Fréquence
                            </label>

                            <div>

                                {{ $planification->abonnement?->frequence ?? '—' }}

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Poids estimé
                            </label>

                            <div>

                                {{ $planification->abonnement?->poids_estime ?? '0' }} kg

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Durée estimée
                            </label>

                            <div>

                                {{ $planification->duree_estimee ?? '0' }} min

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-4">

            {{-- STATUT --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-light">

                    <h5 class="mb-0">
                        🚦 Statut
                    </h5>

                </div>

                <div class="card-body text-center">

                    @php

                        $statusColors = [
                            'planifiee' => 'secondary',
                            'assignee'  => 'info',
                            'en_route'  => 'warning',
                            'en_cours'  => 'primary',
                            'terminee'  => 'success',
                        ];

                    @endphp

                    <span class="badge bg-{{ $statusColors[$planification->statut] ?? 'dark' }} px-4 py-3 fs-6">

                        {{ ucfirst(str_replace('_', ' ', $planification->statut)) }}

                    </span>

                </div>

            </div>

            {{-- PHOTO --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-light">

                    <h5 class="mb-0">
                        📸 Photo de collecte
                    </h5>

                </div>

                <div class="card-body text-center">

                    @if($planification->collecte?->photo)

                        <img src="{{ asset('storage/' . $planification->collecte->photo) }}"
                             class="img-fluid rounded shadow-sm">

                    @else

                        <div class="py-5 text-muted">

                            <i class="bx bx-image fs-1"></i>

                            <p class="mt-2 mb-0">
                                Aucune photo
                            </p>

                        </div>

                    @endif

                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-light">

                    <h5 class="mb-0">
                        ⚡ Actions rapides
                    </h5>

                </div>

                <div class="card-body d-grid gap-2">

                    @if($planification->statut === 'assignee')

                        <form method="POST"
                              action="{{ route('collecteur.start', $planification) }}">

                            @csrf

                            <button class="btn btn-warning w-100">

                                Démarrer la tournée

                            </button>

                        </form>

                    @elseif($planification->statut === 'en_route')

                        <form method="POST"
                              action="{{ route('collecteur.arrive', $planification) }}">

                            @csrf

                            <button class="btn btn-primary w-100">

                                Confirmer l'arrivée

                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
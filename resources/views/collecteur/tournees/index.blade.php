@extends('collecteur.layouts.app')

@section('title', 'Mes tournées du jour')

@section('content')
<div class="container-fluid py-4">
    {{-- Header de la page --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="text-center text-md-start">
                <h2 class="fw-bold mb-1">
                    <i class="bx bx-map-alt text-primary me-2"></i>Mes tournées du jour
                </h2>
                <p class="text-muted mb-0">Suivez et gérez vos étapes de collecte en temps réel.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge bg-label-primary fs-6 px-4 py-3 shadow-sm">
                    <i class="bx bx-calendar me-1"></i> {{ now()->format('d/m/Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Notifications --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <div class="d-flex">
                <i class="bx bx-check-circle me-2 fs-4"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tableau des tournées --}}
    <div class="card border-0 shadow-lg">
        <div class="card-body p-0"> {{-- p-0 pour que le responsive colle aux bords --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4"> Ordre</th>
                            <th>Client</th>
                            <th>Zone / Secteur</th>
                            <!-- <th>Type</th> -->
                            <th>Statut</th>
                            <th>Est. Durée</th>
                            <th class="text-center">Détails</th>
                            <th class="text-center pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

@forelse($tournees as $tournee)

    @php

        $abonnement = $tournee->abonnement;

        $client = $abonnement?->client;

        $clientName =
            optional($client?->user)->name
            ?? optional($tournee->declaration?->user)->name
            ?? 'Client inconnu';

        $statusColors = [
            'planifiee' => 'secondary',
            'assignee'  => 'info',
            'en_route'  => 'warning',
            'en_cours'  => 'primary',
            'terminee'  => 'success',
        ];

    @endphp

    <tr>

        <!-- ORDRE -->
        <td class="ps-4">
            <div class="fw-bold text-primary">
                {{ $tournee->ordre_passage ?? $loop->iteration }}
            </div>
        </td>

        <!-- CLIENT -->
        <td>

            <div class="d-flex flex-column">

                <span class="fw-semibold">
                    {{ $clientName }}
                </span>

                <small class="text-muted">

                    <i class="bx bx-map-pin me-1"></i>

                    {{ $abonnement?->quartier ?? 'Quartier inconnu' }}

                    -
                    Rue {{ $abonnement?->rue ?? 'N/A' }}

                </small>

                @if($abonnement?->porte)
                    <small class="text-info">
                        🚪 Porte : {{ $abonnement->porte }}
                    </small>
                @endif

            </div>

        </td>

        <!-- ZONE -->
        <td>

            <div class="fw-medium">
                {{ optional($tournee->zone)->nom ?? 'N/A' }}
            </div>

            @if($abonnement?->repere)

                <small class="text-muted d-block text-truncate"
                       style="max-width: 180px;">

                    📍 {{ $abonnement->repere }}

                </small>

            @endif

        </td>

        <!-- TYPE
        <td>

            <span class="badge bg-label-secondary">

                {{ ucfirst($tournee->type_collecte) }}

            </span>

        </td> -->

        <!-- STATUT -->
        <td>

            <span class="badge bg-{{ $statusColors[$tournee->statut] ?? 'dark' }}">

                {{ ucfirst(str_replace('_', ' ', $tournee->statut)) }}

            </span>

        </td>

        <!-- DURÉE -->
        <td>

            <span class="text-info fw-medium">

                <i class="bx bx-time-five me-1"></i>

                {{ $tournee->duree_estimee ?? 0 }} min

            </span>

        </td>

        <!-- DETAILS -->
        <td class="text-center">

            <a href="{{ route('collecteur.show', $tournee->id) }}"
               class="btn btn-sm btn-outline-info">

                <i class="bx bx-show-alt"></i>

            </a>

        </td>

        <!-- ACTIONS -->
        <td class="text-center pe-4">

            @if($tournee->statut === 'assignee')

                <form method="POST"
                      action="{{ route('collecteur.start', $tournee) }}"
                      class="d-inline">

                    @csrf

                    <button type="submit"
                            class="btn btn-warning btn-sm">

                        Démarrer

                    </button>

                </form>

            @elseif($tournee->statut === 'en_route')

                <form method="POST"
                      action="{{ route('collecteur.arrive', $tournee) }}"
                      class="d-inline">

                    @csrf

                    <button type="submit"
                            class="btn btn-primary btn-sm">

                        Arrivé

                    </button>

                </form>

            @elseif($tournee->statut === 'en_cours')

                <button type="button"
                        class="btn btn-success btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#finishModal{{ $tournee->id }}">

                    Collecter

                </button>

            @else

                <span class="badge bg-label-success">
                    ✔ Terminée
                </span>

            @endif

        </td>

    </tr>

@empty

    <tr>

        <td colspan="8" class="text-center py-5">

            <div class="text-muted">

                <i class="bx bx-calendar-x display-1 opacity-25"></i>

                <div class="mt-3 fw-semibold">
                    Aucune tournée prévue aujourd'hui.
                </div>

            </div>

        </td>

    </tr>

@endforelse

</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection<
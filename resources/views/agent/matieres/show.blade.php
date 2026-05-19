@extends('agent.layouts.app')

@section('content')
<div class="container-fluid py-4">

    <!-- En-tête -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="fw-bold text-primary mb-1">
                    <i class="bx bx-package me-2"></i>{{ $type_dechet }}
                </h3>
                <p class="text-muted mb-0">
                    Détails et historique de la matière collectée
                </p>
            </div>

            <a href="{{ route('agent.matieres.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Retour
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bx bx-bar-chart-alt text-primary fs-1 mb-2"></i>
                    <div class="text-muted">Quantité totale</div>
                    <h3 class="fw-bold mt-2">
                        {{ number_format($stats['quantite_totale'], 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bx bx-layer text-success fs-1 mb-2"></i>
                    <div class="text-muted">Nombre de tris</div>
                    <h3 class="fw-bold mt-2">
                        {{ $stats['nombre_tries'] }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bx bx-time text-warning fs-1 mb-2"></i>
                    <div class="text-muted">Dernière entrée</div>
                    <h6 class="fw-bold mt-2">
                        {{ $stats['derniere_entree']?->format('d/m/Y H:i') ?? '-' }}
                    </h6>
                </div>
            </div>
        </div>

    </div>

    <!-- Historique cartes -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold">
                <i class="bx bx-history me-2"></i>Historique
            </h5>
        </div>

        <div class="card-body">

            @forelse($tries as $try)
                <div class="card border mb-3 shadow-sm">
                    <div class="card-body">

                        <div class="row align-items-center g-3">

                            <div class="col-md-3">
                                <small class="text-muted">Date</small>
                                <div class="fw-bold">
                                    {{ $try->created_at?->format('d/m/Y H:i') }}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <small class="text-muted">Quantité</small>
                                <div class="fw-bold text-primary">
                                    {{ number_format($try->quantite_trier, 2) }}
                                    {{ $try->unite }}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <small class="text-muted">Pesage</small>
                                <div class="fw-bold">
                                    #{{ $try->pesage?->id ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <small class="text-muted">Client</small>
                                <div class="fw-bold">
                                    {{ $try->pesage?->collecte?->planification?->abonnement?->client?->nom ?? 'Non défini' }}
                                </div>
                            </div>

                            <div class="col-md-2 text-end">
                                <span class="badge bg-success px-3 py-2">
                                    Disponible
                                </span>
                            </div>

                        </div>

                    </div>
                </div>

            @empty

                <div class="text-center py-5">
                    <i class="bx bx-package display-1 text-muted"></i>
                    <h5 class="mt-3">Aucune donnée trouvée</h5>
                    <p class="text-muted">
                        Aucun tri enregistré pour cette matière.
                    </p>
                </div>

            @endforelse

        </div>
    </div>

</div>
@endsection
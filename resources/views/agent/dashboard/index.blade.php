
<!-- ========================= -->
@extends('layouts.app')
@section('title', 'Dashboard Agent - EcoFlux')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="row align-items-center g-4">
            <div class="col-12">
                <div class="welcome-section">
                    <h1 class="welcome-title">
                        Bonjour, {{ auth()->user()->name ?? 'Agent' }}
                    </h1>
                    <p class="welcome-subtitle">
                        Bienvenue sur votre tableau de bord agent EcoFlux.
                    </p>
                </div>
            </div>
        </div>
    </div>


<!-- -- =========================
    STATISTIQUES
{{-- =========================================================
    KPI / STATISTIQUES PROFESSIONNELLES
========================================================= --}} -->
<div class="row g-4 mb-4">

    {{-- COLLECTES --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0 h-100 dashboard-card">

            <div class="card-body">

                <div class="d-flex align-items-start justify-content-between">

                    <div>

                        <span class="text-muted small">
                            Collectes aujourd'hui
                        </span>

                        <h2 class="mt-2 mb-1 fw-bold text-dark">
                            {{ $stats['collectes_today'] }}
                        </h2>

                        <p class="text-muted small mb-0">
                            Collectes effectuées ce jour
                        </p>

                    </div>

                    <div class="avatar bg-primary bg-opacity-10 p-3 rounded-3">

                        <i class="bx bx-package fs-1 text-primary"></i>

                    </div>

                </div>

                <div class="d-flex align-items-center justify-content-between mt-4">

                    <small class="text-success fw-semibold">

                        <i class="bx bx-up-arrow-alt"></i>
                        Activité en cours

                    </small>

                    <a href="{{ route('agent.collectes.index') }}"
                       class="btn btn-sm btn-light rounded-pill">

                        Détails

                    </a>

                </div>

            </div>

        </div>
    </div>

    {{-- POIDS --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0 h-100 dashboard-card">

            <div class="card-body">

                <div class="d-flex align-items-start justify-content-between">

                    <div>

                        <span class="text-muted small">
                            Poids collecté
                        </span>

                        <h2 class="mt-2 mb-1 fw-bold text-dark">
                            {{ number_format($poids_today, 2) }} kg
                        </h2>

                        <p class="text-muted small mb-0">
                            Quantité enregistrée aujourd'hui
                        </p>

                    </div>

                    <div class="avatar bg-success bg-opacity-10 p-3 rounded-3">

                        <i class="bx bx-trending-up fs-1 text-success"></i>

                    </div>

                </div>

                <div class="d-flex align-items-center justify-content-between mt-4">

                    <small class="text-success fw-semibold">

                        <i class="bx bx-check-circle"></i>
                        Pesages validés

                    </small>

                    <span class="badge bg-label-success">
                        Actif
                    </span>

                </div>

            </div>

        </div>
    </div>

    {{-- TRI --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0 h-100 dashboard-card">

            <div class="card-body">

                <div class="d-flex align-items-start justify-content-between">

                    <div>

                        <span class="text-muted small">
                            Quantité triée
                        </span>

                        <h2 class="mt-2 mb-1 fw-bold text-dark">
                            {{ number_format($quantite_triee_today, 2) }} kg
                        </h2>

                        <p class="text-muted small mb-0">
                            Déchets triés et recyclables
                        </p>

                    </div>

                    <div class="avatar bg-info bg-opacity-10 p-3 rounded-3">

                        <i class="bx bx-filter-alt fs-1 text-info"></i>

                    </div>

                </div>

                <div class="d-flex align-items-center justify-content-between mt-4">

                    <small class="text-info fw-semibold">

                        <i class="bx bx-recycle"></i>
                        Tri écologique

                    </small>

                    <span class="badge bg-label-info">
                        Recyclage
                    </span>

                </div>

            </div>

        </div>
    </div>

    {{-- PRODUITS --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0 h-100 dashboard-card">

            <div class="card-body">

                <div class="d-flex align-items-start justify-content-between">

                    <div>

                        <span class="text-muted small">
                            Produits fabriqués
                        </span>

                        <h2 class="mt-2 mb-1 fw-bold text-dark">
                            {{ $produits_fabriqués_today }}
                        </h2>

                        <p class="text-muted small mb-0">
                            Production réalisée aujourd'hui
                        </p>

                    </div>

                    <div class="avatar bg-warning bg-opacity-10 p-3 rounded-3">

                        <i class="bx bx-box fs-1 text-warning"></i>

                    </div>

                </div>

                <div class="d-flex align-items-center justify-content-between mt-4">

                    <small class="text-warning fw-semibold">

                        <i class="bx bx-cube"></i>
                        Production active

                    </small>

                    <span class="badge bg-label-warning">
                        Fabrication
                    </span>

                </div>

            </div>

        </div>
    </div>

</div>

<!-- =========================
    CONTENU PRINCIPAL
========================= -->
<div class="row">

    <!-- Collectes récentes -->
    <div class="col-lg-7 mb-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-0 fw-bold">
                        Collectes récentes
                    </h5>

                    <small class="text-muted">
                        Dernières activités des collectes
                    </small>
                </div>

                <a href="{{ route('agent.collectes.index') }}"
                   class="btn btn-sm btn-outline-primary">
                    Voir tout
                </a>

            </div>

            <div class="card-body">

                @forelse($collectes_recentes as $collecte)

                    <div class="d-flex align-items-center justify-content-between py-3 border-bottom">

                        <div class="d-flex align-items-center">

                            <div class="avatar avatar-md me-3">

                                @if($collecte->photo)

                                    <img src="{{ asset('storage/' . $collecte->photo) }}"
                                         class="rounded-circle"
                                         alt="Photo">

                                @else

                                    <div class="avatar-initial bg-label-secondary rounded-circle">
                                        <i class="bx bx-package"></i>
                                    </div>

                                @endif

                            </div>

                            <div>

                                <h6 class="mb-1 fw-semibold">
                                    {{ $collecte->planification->abonnement->client->nom ?? 'Client inconnu' }}
                                </h6>

                                <small class="text-muted">
                                    {{ $collecte->created_at->diffForHumans() }}
                                </small>

                            </div>

                        </div>

                        <span class="badge bg-label-{{ $collecte->statut == 'terminee' ? 'success' : 'warning' }}">
                            {{ ucfirst($collecte->statut) }}
                        </span>

                    </div>

                @empty

                    <div class="text-center py-5">
                        <i class="bx bx-package display-6 text-muted"></i>

                        <p class="text-muted mt-3 mb-0">
                            Aucune collecte récente
                        </p>
                    </div>

                @endforelse

            </div>

        </div>

    </div>

    <!-- Alertes Stock -->
    <div class="col-lg-5 mb-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-0 fw-bold">
                        Alertes stock
                    </h5>

                    <small class="text-muted">
                        Produits en quantité faible
                    </small>
                </div>

                <a href="{{ route('agent.stocks.index') }}"
                   class="btn btn-sm btn-outline-danger">
                    Voir tout
                </a>

            </div>

            <div class="card-body">

                @forelse($stocks_faibles as $stock)

                    <div class="d-flex align-items-center justify-content-between py-3 border-bottom">

                        <div class="d-flex align-items-center">

                            <div class="avatar avatar-sm me-3">
                                <div class="avatar-initial bg-label-danger rounded-circle">
                                    <i class="bx bx-error"></i>
                                </div>
                            </div>

                            <div>
                                <h6 class="mb-1 fw-semibold">
                                    {{ $stock->produit->nom ?? 'Produit' }}
                                </h6>

                                <small class="text-muted">
                                    {{ $stock->quantite_disponible }}
                                    {{ $stock->unite_mesure }}
                                </small>
                            </div>

                        </div>

                        <span class="badge bg-danger">
                            Faible
                        </span>

                    </div>

                @empty

                    <div class="text-center py-5">
                        <i class="bx bx-check-circle display-6 text-success"></i>

                        <p class="text-muted mt-3 mb-0">
                            Aucun stock faible
                        </p>
                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

<!-- =========================
    ACTIVITES RECENTES
========================= -->
<div class="row">

    <div class="col-12 mb-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-transparent border-bottom">

                <h5 class="fw-bold mb-0">
                    Activités récentes
                </h5>

            </div>

            <div class="card-body">

                @forelse($activites as $activite)

                    <div class="d-flex align-items-start py-3 border-bottom">

                        <div class="avatar avatar-sm me-3">

                            <div class="avatar-initial bg-label-{{ $activite['type'] == 'pesage' ? 'primary' : 'info' }} rounded-circle">

                                <i class="bx bx-{{ $activite['type'] == 'pesage' ? 'trending-up' : 'filter-alt' }}"></i>

                            </div>

                        </div>

                        <div class="flex-grow-1">

                            <p class="mb-1 fw-semibold">
                                {{ $activite['message'] }}
                            </p>

                            <small class="text-muted">
                                {{ $activite['client'] }}
                                •
                                {{ $activite['date']->diffForHumans() }}
                            </small>

                        </div>

                    </div>

                @empty

                    <div class="text-center py-4">

                        <i class="bx bx-history display-6 text-muted"></i>

                        <p class="text-muted mt-3 mb-0">
                            Aucune activité récente
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

<!-- =========================
    ACTIONS RAPIDES
========================= -->
<div class="row">

    <div class="col-12">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-transparent border-bottom">

                <h5 class="fw-bold mb-0">
                    Actions rapides
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('agent.pesages.create') }}"
                           class="btn btn-outline-primary w-100 py-3">

                            <i class="bx bx-plus-circle fs-4 d-block mb-1"></i>

                            Nouveau pesage
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('tries.create') }}"
                           class="btn btn-outline-success w-100 py-3">

                            <i class="bx bx-filter-alt fs-4 d-block mb-1"></i>

                            Nouveau tri
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('agent.produits.create') }}"
                           class="btn btn-outline-warning w-100 py-3">

                            <i class="bx bx-box fs-4 d-block mb-1"></i>

                            Nouveau produit
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('agent.rapports.index') }}"
                           class="btn btn-outline-info w-100 py-3">

                            <i class="bx bx-bar-chart-alt-2 fs-4 d-block mb-1"></i>

                            Générer rapport
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
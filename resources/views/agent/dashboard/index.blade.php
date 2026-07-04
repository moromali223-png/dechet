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
                    <h1 class="welcome-title">Bonjour, {{ auth()->user()->name ?? 'Agent' }}</h1>
                    <p class="welcome-subtitle">Bienvenue sur votre tableau de bord agent EcoFlux.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI -->
    <div class="row g-4 mb-4">
        <!-- Collectes -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100 dashboard-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Collectes aujourd'hui</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ $stats['collectes_today'] ?? 0 }}</h2>
                            <p class="text-muted small mb-0">Collectes effectuées ce jour</p>
                        </div>
                        <div class="avatar bg-primary bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-package fs-1 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Poids -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100 dashboard-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Poids collecté</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($poids_today ?? 0, 2) }} kg</h2>
                            <p class="text-muted small mb-0">Quantité enregistrée aujourd'hui</p>
                        </div>
                        <div class="avatar bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-trending-up fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tri -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100 dashboard-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Quantité triée</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($quantite_triee_today ?? 0, 2) }} kg</h2>
                            <p class="text-muted small mb-0">Déchets triés et recyclables</p>
                        </div>
                        <div class="avatar bg-info bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-filter-alt fs-1 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produits -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100 dashboard-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Produits fabriqués</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ $produits_fabriqués_today ?? 0 }}</h2>
                            <p class="text-muted small mb-0">Production réalisée aujourd'hui</p>
                        </div>
                        <div class="avatar bg-warning bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-box fs-1 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Collectes récentes -->
    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold">Collectes récentes</h5>
                        <small class="text-muted">Dernières activités des collectes</small>
                    </div>
                    <a href="{{ route('agent.collectes.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="card-body">
                    @forelse($collectesRecentes as $collecte)
                        <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-md me-3">
                                    @if($collecte->photo)
                                        <img src="{{ asset('storage/' . $collecte->photo) }}" class="rounded-circle" alt="Photo">
                                    @else
                                        <div class="avatar-initial bg-label-secondary rounded-circle">
                                            <i class="bx bx-package"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold">
                                        {{ $collecte->planification?->abonnement?->user?->name ?? 'Client inconnu' }}
                                    </h6>
                                    <small class="text-muted">{{ $collecte->created_at?->diffForHumans() }}</small>
                                </div>
                            </div>
                            <span class="badge bg-label-{{ $collecte->statut == 'terminee' ? 'success' : 'warning' }}">
                                {{ ucfirst($collecte->statut ?? 'inconnu') }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bx bx-package display-6 text-muted"></i>
                            <p class="text-muted mt-3">Aucune collecte récente</p>
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
                        <h5 class="mb-0 fw-bold">Alertes stock</h5>
                        <small class="text-muted">Produits en quantité faible</small>
                    </div>
                    <a href="{{ route('agent.stocks.index') }}" class="btn btn-sm btn-outline-danger">Voir tout</a>
                </div>
                <div class="card-body">
                    @forelse($stocksFaibles as $stock)
                        <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <div class="avatar-initial bg-label-danger rounded-circle">
                                        <i class="bx bx-error"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold">{{ $stock->produit?->nom ?? 'Produit' }}</h6>
                                    <small class="text-muted">
                                        {{ $stock->quantite_disponible ?? 0 }} {{ $stock->unite_mesure ?? '' }}
                                    </small>
                                </div>
                            </div>
                            <span class="badge bg-danger">Faible</span>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bx bx-check-circle display-6 text-success"></i>
                            <p class="text-muted mt-3">Aucun stock faible</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Activités récentes + Actions rapides -->
    <!-- (Je peux te les corriger aussi si besoin) -->

</div>
@endsection
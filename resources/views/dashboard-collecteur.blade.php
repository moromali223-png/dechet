@extends('layouts.app')
@section('title', 'Dashboard Collecteur - EcoFlux')

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
                        Bonjour, {{ $userName ?? 'Collecteur' }}
                    </h1>
                    <p class="welcome-subtitle">
                        Bienvenue sur votre tableau de bord collecteur EcoFlux.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-4">
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Collectes Totales</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($collectesCount, 0, ',', ' ') }}</h2>
                            <p class="text-muted small mb-0">Missions effectuées</p>
                        </div>
                        <div class="avatar bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-package fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Aujourd'hui</span>
                            <h2 class="mt-2 mb-1 fw-bold text-primary">{{ $collectesAujourdHui ?? 0 }}</h2>
                            <p class="text-muted small mb-0">Collectes planifiées</p>
                        </div>
                        <div class="avatar bg-primary bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-calendar fs-1 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">En Cours</span>
                            <h2 class="mt-2 mb-1 fw-bold text-warning">{{ $collectesEnCours ?? 0 }}</h2>
                            <p class="text-muted small mb-0">Missions actives</p>
                        </div>
                        <div class="avatar bg-warning bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-loader fs-1 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Poids Total</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($totalPoidsCollecte ?? 0, 1) }} <small class="fs-5">Kg</small></h2>
                            <p class="text-muted small mb-0">Collecté</p>
                        </div>
                        <div class="avatar bg-info bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-weight fs-1 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Taux de complétion + Zones -->
    <div class="row g-4 mt-4">
        <div class="col-xl-6 col-md-6">
            <div class="card shadow-sm border-0 h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Taux de Complétion</span>
                            <h2 class="mt-2 mb-1 fw-bold text-success">{{ $tauxCompletion }}%</h2>
                            <p class="text-muted small mb-0">Missions terminées</p>
                        </div>
                        <div class="avatar bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-trending-up fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6">
            <div class="card shadow-sm border-0 h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Zones Couvertes</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($zonesCount, 0, ',', ' ') }}</h2>
                            <p class="text-muted small mb-0">Zones assignées</p>
                        </div>
                        <div class="avatar bg-info bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-map fs-1 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activités récentes -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0 bg-transparent">
                    <h5 class="mb-0 text-dark">Activités Récentes</h5>
                </div>
                <div class="card-body">
                    @if($recentCollectes->isNotEmpty())
                        <!-- Tu peux ajouter un tableau ici plus tard -->
                        <p class="text-muted">Vous avez {{ $recentCollectes->count() }} collectes récentes.</p>
                    @else
                        <p class="text-muted text-center py-4">Aucune collecte effectuée pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
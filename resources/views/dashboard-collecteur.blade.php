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
                        Bonjour, {{ auth()->user()->name ?? 'Collecteur' }}
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
        <div class="col-xl-6 col-md-6">
            <div class="card shadow-sm border-0 h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Collectes totales</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($collectesCount, 0, ',', ' ') }}</h2>
                            <p class="text-muted small mb-0">Nombre de collectes</p>
                        </div>
                        <div class="avatar bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-package fs-1 text-success"></i>
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
                            <span class="text-muted small">Zones couvertes</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($zonesCount, 0, ',', ' ') }}</h2>
                            <p class="text-muted small mb-0">Zones de collecte</p>
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
                    <h5 class="mb-0 text-dark">Activités récentes</h5>
                </div>
                <div class="card-body">
                    <p>Contenu à définir selon les besoins du collecteur.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
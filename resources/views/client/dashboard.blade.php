@extends('layouts.app')
@section('title', 'Dashboard Client - EcoFlux')

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
                        Bonjour, {{ auth()->user()->name ?? 'Client' }}
                    </h1>
                    <p class="welcome-subtitle">
                        Bienvenue sur votre tableau de bord client EcoFlux.
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
                            <span class="text-muted small">Mes commandes</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($commandesCount, 0, ',', ' ') }}</h2>
                            <p class="text-muted small mb-0">Total de mes commandes</p>
                        </div>
                        <div class="avatar bg-primary bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-shopping-bag fs-1 text-primary"></i>
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
                            <span class="text-muted small">Paiements effectués</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($paiementsCount, 0, ',', ' ') }}</h2>
                            <p class="text-muted small mb-0">Transactions validées</p>
                        </div>
                        <div class="avatar bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-credit-card fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes dernières commandes -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0 bg-transparent">
                    <h5 class="mb-0 text-dark">Mes dernières commandes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentCommandes as $commande)
                                <tr>
                                    <td>{{ $commande->id }}</td>
                                    <td>{{ $commande->date_commande->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $commande->statut === 'en_attente' ? 'warning' : 'success' }}">
                                            {{ $commande->statut }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
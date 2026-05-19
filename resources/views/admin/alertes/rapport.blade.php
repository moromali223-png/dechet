@extends('layouts.app')

@section('title', 'Rapport des Alertes')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h4 class="card-title mb-1">
                <i class="bx bx-chart-line text-primary me-2"></i>
                Rapport des Alertes de Stock
            </h4>
            <small class="text-muted">Analyse détaillée des niveaux de stock</small>
        </div>
        <div>
            <a href="{{ route('alertes.index') }}" class="btn btn-outline-primary btn-sm me-2">
                <i class="bx bx-bell me-1"></i> Voir Alertes
            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-printer me-1"></i> Imprimer
            </button>
        </div>
    </div>

    <div class="card-body">
        <!-- Période du rapport -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bx bx-calendar me-2"></i>
                            Période du Rapport
                        </h6>
                        <p class="mb-0">
                            Généré le {{ now()->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bx bx-time me-2"></i>
                            Dernière Mise à Jour
                        </h6>
                        <p class="mb-0">
                            Données en temps réel
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques générales -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-primary shadow-sm">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2" style="width: 50px; height: 50px; background: linear-gradient(135deg, #007bff, #0056b3); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bx bx-package text-white" style="font-size: 24px;"></i>
                        </div>
                        <h4 class="text-primary mb-1">{{ $totalProduits }}</h4>
                        <p class="text-muted small mb-0">Total Produits</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success shadow-sm">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2" style="width: 50px; height: 50px; background: linear-gradient(135deg, #28a745, #1e7e34); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bx bx-check-circle text-white" style="font-size: 24px;"></i>
                        </div>
                        <h4 class="text-success mb-1">{{ $produitsNormaux }}</h4>
                        <p class="text-muted small mb-0">Stock Normal</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning shadow-sm">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2" style="width: 50px; height: 50px; background: linear-gradient(135deg, #ffc107, #e0a800); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bx bx-error text-white" style="font-size: 24px;"></i>
                        </div>
                        <h4 class="text-warning mb-1">{{ $produitsEnAlerte }}</h4>
                        <p class="text-muted small mb-0">En Alerte</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-danger shadow-sm">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2" style="width: 50px; height: 50px; background: linear-gradient(135deg, #dc3545, #bd2130); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bx bx-x-circle text-white" style="font-size: 24px;"></i>
                        </div>
                        <h4 class="text-danger mb-1">{{ $produitsEpuises }}</h4>
                        <p class="text-muted small mb-0">Épuisés</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique de répartition -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-pie-chart me-2"></i>
                            Répartition des Stocks
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="stockChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détail des produits en alerte -->
        @if($produitsEnAlerte > 0 || $produitsEpuises > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-list-ul me-2"></i>
                            Détail des Produits en Alerte
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th>Stock Actuel</th>
                                        <th>Seuil Alerte</th>
                                        <th>Niveau d'Alerte</th>
                                        <th>Dernière Mise à Jour</th>
                                        <th>Actions Recommandées</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stocksEnAlerte as $stock)
                                    <tr class="{{ $stock->quantite_disponible <= 0 ? 'table-danger' : ($stock->quantite_disponible <= $stock->seuil_alerte * 0.5 ? 'table-warning' : 'table-info') }}">
                                        <td>
                                            <div class="fw-semibold">{{ $stock->nom }}</div>
                                            @if($stock->produit)
                                                <small class="text-muted">{{ $stock->produit->nom }}</small>
                                            @endif
                                        </td>
                                        <td class="fw-bold">
                                            <span class="{{ $stock->quantite_disponible <= 0 ? 'text-danger' : ($stock->quantite_disponible <= $stock->seuil_alerte * 0.5 ? 'text-warning' : 'text-info') }}">
                                                {{ number_format($stock->quantite_disponible, 2) }} {{ $stock->unite_mesure }}
                                            </span>
                                        </td>
                                        <td>{{ $stock->seuil_alerte }} {{ $stock->unite_mesure }}</td>
                                        <td>
                                            @if($stock->quantite_disponible <= 0)
                                                <span class="badge bg-danger">RUPTURE</span>
                                            @elseif($stock->quantite_disponible <= $stock->seuil_alerte * 0.5)
                                                <span class="badge bg-warning">TRÈS FAIBLE</span>
                                            @else
                                                <span class="badge bg-info">FAIBLE</span>
                                            @endif
                                        </td>
                                        <td>{{ $stock->updated_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('stock-entree.create') }}" class="btn btn-outline-primary">
                                                    <i class="bx bx-plus"></i> Réapprovisionner
                                                </a>
                                                <a href="{{ route('produits.edit', $stock->produit_id ?? 0) }}" class="btn btn-outline-secondary">
                                                    <i class="bx bx-edit"></i> Modifier
                                                </a>
                                            </div>
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
        @endif

        <!-- Résumé et recommandations -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-bulb me-2"></i>
                            Recommandations
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Actions Immédiates Requises :</h6>
                                <ul class="list-unstyled">
                                    @if($produitsEpuises > 0)
                                    <li class="mb-2">
                                        <i class="bx bx-x-circle text-danger me-2"></i>
                                        <strong>{{ $produitsEpuises }}</strong> produit(s) en rupture de stock - Réapprovisionnement urgent
                                    </li>
                                    @endif
                                    @if($produitsEnAlerte > 0)
                                    <li class="mb-2">
                                        <i class="bx bx-error text-warning me-2"></i>
                                        <strong>{{ $produitsEnAlerte }}</strong> produit(s) avec stock faible - Planifier réapprovisionnement
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>État Général :</h6>
                                <div class="progress mb-3" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: {{ ($produitsNormaux / $totalProduits) * 100 }}%">
                                        {{ round(($produitsNormaux / $totalProduits) * 100) }}% Normal
                                    </div>
                                    <div class="progress-bar bg-warning" style="width: {{ ($produitsEnAlerte / $totalProduits) * 100 }}%">
                                        {{ round(($produitsEnAlerte / $totalProduits) * 100) }}% Alerte
                                    </div>
                                    <div class="progress-bar bg-danger" style="width: {{ ($produitsEpuises / $totalProduits) * 100 }}%">
                                        {{ round(($produitsEpuises / $totalProduits) * 100) }}% Épuisé
                                    </div>
                                </div>
                                <p class="mb-0">
                                    <strong>Taux de conformité :</strong>
                                    <span class="text-{{ $produitsNormaux / $totalProduits > 0.8 ? 'success' : ($produitsNormaux / $totalProduits > 0.6 ? 'warning' : 'danger') }}">
                                        {{ round(($produitsNormaux / $totalProduits) * 100) }}%
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('stockChart').getContext('2d');
    const stockChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Stock Normal', 'Stock Faible', 'Stock Épuisé'],
            datasets: [{
                data: [{{ $produitsNormaux }}, {{ $produitsEnAlerte }}, {{ $produitsEpuises }}],
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#dc3545'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((context.parsed / total) * 100);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>

<style>
@media print {
    .btn, .card-header .btn-group {
        display: none !important;
    }
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
    }
}
</style>
@endsection
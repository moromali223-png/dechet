@extends('layouts.app')

@section('title', 'Alertes de Stock')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h4 class="card-title mb-1">
                <i class="bx bx-bell text-warning me-2"></i>
                Alertes de Stock
            </h4>
            <small class="text-muted">Produits nécessitant une attention immédiate</small>
        </div>
        <div>
            <a href="{{ route('inventaire.index') }}" class="btn btn-outline-primary btn-sm me-2">
                <i class="bx bx-list-check me-1"></i> Voir Inventaire
            </a>
            <a href="{{ route('alertes.rapport') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-file me-1"></i> Rapport
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Statistiques des alertes -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-danger shadow-sm">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2" style="width: 50px; height: 50px; background: linear-gradient(135deg, #ff4444, #cc0000); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bx bx-error text-white" style="font-size: 24px;"></i>
                        </div>
                        <h4 class="text-danger mb-1">{{ $totalAlertes }}</h4>
                        <p class="text-muted small mb-0">Total Alertes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-warning shadow-sm">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2" style="width: 50px; height: 50px; background: linear-gradient(135deg, #ff8800, #cc6600); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bx bx-error-circle text-white" style="font-size: 24px;"></i>
                        </div>
                        <h4 class="text-warning mb-1">{{ $alertesCritiques }}</h4>
                        <p class="text-muted small mb-0">Stock Épuisé</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-info shadow-sm">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2" style="width: 50px; height: 50px; background: linear-gradient(135deg, #0099cc, #006699); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bx bx-info-circle text-white" style="font-size: 24px;"></i>
                        </div>
                        <h4 class="text-info mb-1">{{ $alertesModerees }}</h4>
                        <p class="text-muted small mb-0">Stock Faible</p>
                    </div>
                </div>
            </div>
        </div>

        @if($totalAlertes > 0)
            <!-- Alertes par niveau d'urgence -->
            <div class="row">
                <!-- Alertes Critiques -->
                @if($alertesParUrgence['critique']->count() > 0)
                <div class="col-12 mb-4">
                    <h5 class="text-danger mb-3">
                        <i class="bx bx-error-circle me-2"></i>
                        Stock Épuisé ({{ $alertesParUrgence['critique']->count() }})
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover border-danger">
                            <thead class="table-danger">
                                <tr>
                                    <th>Produit</th>
                                    <th>Stock Actuel</th>
                                    <th>Seuil Alerte</th>
                                    <th>État</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alertesParUrgence['critique'] as $stock)
                                <tr class="table-danger">
                                    <td>
                                        <div class="fw-semibold">{{ $stock->nom }}</div>
                                        @if($stock->produit)
                                            <small class="text-muted">{{ $stock->produit->nom }}</small>
                                        @endif
                                    </td>
                                    <td class="text-danger fw-bold">{{ number_format($stock->quantite_disponible, 2) }} {{ $stock->unite_mesure }}</td>
                                    <td>{{ $stock->seuil_alerte }} {{ $stock->unite_mesure }}</td>
                                    <td><span class="badge bg-danger">RUPTURE</span></td>
                                    <td>
                                        <a href="{{ route('stock-entree.create') }}" class="btn btn-sm btn-danger">
                                            <i class="bx bx-plus me-1"></i> Réapprovisionner
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Alertes Élevées -->
                @if($alertesParUrgence['elevee']->count() > 0)
                <div class="col-12 mb-4">
                    <h5 class="text-warning mb-3">
                        <i class="bx bx-error me-2"></i>
                        Stock Très Faible ({{ $alertesParUrgence['elevee']->count() }})
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover border-warning">
                            <thead class="table-warning">
                                <tr>
                                    <th>Produit</th>
                                    <th>Stock Actuel</th>
                                    <th>Seuil Alerte</th>
                                    <th>État</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alertesParUrgence['elevee'] as $stock)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $stock->nom }}</div>
                                        @if($stock->produit)
                                            <small class="text-muted">{{ $stock->produit->nom }}</small>
                                        @endif
                                    </td>
                                    <td class="text-warning fw-bold">{{ number_format($stock->quantite_disponible, 2) }} {{ $stock->unite_mesure }}</td>
                                    <td>{{ $stock->seuil_alerte }} {{ $stock->unite_mesure }}</td>
                                    <td><span class="badge bg-warning">TRÈS FAIBLE</span></td>
                                    <td>
                                        <a href="{{ route('stock-entree.create') }}" class="btn btn-sm btn-warning">
                                            <i class="bx bx-plus me-1"></i> Réapprovisionner
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Alertes Modérées -->
                @if($alertesParUrgence['moderee']->count() > 0)
                <div class="col-12 mb-4">
                    <h5 class="text-info mb-3">
                        <i class="bx bx-info-circle me-2"></i>
                        Stock Faible ({{ $alertesParUrgence['moderee']->count() }})
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover border-info">
                            <thead class="table-info">
                                <tr>
                                    <th>Produit</th>
                                    <th>Stock Actuel</th>
                                    <th>Seuil Alerte</th>
                                    <th>État</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alertesParUrgence['moderee'] as $stock)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $stock->nom }}</div>
                                        @if($stock->produit)
                                            <small class="text-muted">{{ $stock->produit->nom }}</small>
                                        @endif
                                    </td>
                                    <td class="text-info fw-bold">{{ number_format($stock->quantite_disponible, 2) }} {{ $stock->unite_mesure }}</td>
                                    <td>{{ $stock->seuil_alerte }} {{ $stock->unite_mesure }}</td>
                                    <td><span class="badge bg-info">FAIBLE</span></td>
                                    <td>
                                        <a href="{{ route('stock-entree.create') }}" class="btn btn-sm btn-info">
                                            <i class="bx bx-plus me-1"></i> Réapprovisionner
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        @else
            <!-- Aucun produit en alerte -->
            <div class="text-center py-5">
                <div class="avatar mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="bx bx-check text-white" style="font-size: 40px;"></i>
                </div>
                <h4 class="text-success mb-2">Aucune Alerte Active</h4>
                <p class="text-muted">Tous vos stocks sont dans des niveaux satisfaisants.</p>
                <a href="{{ route('inventaire.index') }}" class="btn btn-success">
                    <i class="bx bx-show me-1"></i> Voir l'Inventaire Complet
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
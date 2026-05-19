@extends('layouts.app')

@section('title', 'Stock - Agent')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h4 class="card-title mb-1">Mon Stock</h4>
                <small class="text-muted">Vue Agent - Inventaire en temps réel</small>
            </div>
            <a href="{{ route('agent.dashboard') }}" class="btn btn-outline-secondary btn-sm">Retour Dashboard</a>
        </div>

        <div class="card-body">

            <!-- Statistiques -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <p class="text-muted small mb-1">Produits suivis</p>
                            <h3 class="mb-0">{{ $totalProduits }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <p class="text-muted small mb-1">Stock total</p>
                            <h3 class="mb-0">{{ number_format($stockTotal, 2, ',', ' ') }} {{ $stocks->first()?->unite_mesure ?? 'kg' }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <p class="text-muted small mb-1">Valeur totale</p>
                            <h3 class="mb-0">{{ number_format($valeurTotale, 0, ',', ' ') }} FCFA</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 {{ $produitsEnAlerte > 0 ? 'border-danger' : 'border-success' }}">
                        <div class="card-body text-center">
                            <p class="text-muted small mb-1">En alerte</p>
                            <h3 class="mb-0 text-{{ $produitsEnAlerte > 0 ? 'danger' : 'success' }}">{{ $produitsEnAlerte }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recherche -->
            <div class="mb-3">
                <form method="GET" class="row g-2">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Rechercher par nom de produit..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Rechercher</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('agent.stocks.index') }}" class="btn btn-secondary w-100">Réinitialiser</a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('agent.stocks.index', ['alerte' => 1]) }}" 
                           class="btn btn-warning w-100">Voir  en alerte</a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Produit</th>
                            <th class="text-end">Quantité</th>
                            <th class="text-end">Prix Unitaire</th>
                            <th class="text-end">Valeur Totale</th>
                            <th class="text-center">Seuil</th>
                            <th class="text-center">Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                            <tr class="{{ $stock->quantite_disponible <= $stock->seuil_alerte ? 'table-danger' : '' }}">
                                <td class="font-monospace">{{ $stock->code_stock ?? '—' }}</td>
                                <td>
                                    <strong>{{ $stock->nom ?? ($stock->produit?->nom ?? 'N/A') }}</strong>
                                    @if($stock->produit)
                                        <small class="text-muted d-block">{{ $stock->produit->type ?? '' }}</small>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold">
                                    {{ number_format($stock->quantite_disponible, 2, ',', ' ') }} {{ $stock->unite_mesure }}
                                </td>
                                <td class="text-end">{{ number_format($stock->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                <td class="text-end">{{ number_format($stock->valeur_totale ?? $stock->quantite_disponible * $stock->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                <td class="text-center">{{ $stock->seuil_alerte }} {{ $stock->unite_mesure }}</td>
                                <td class="text-center">
                                    @if($stock->quantite_disponible <= $stock->seuil_alerte)
                                        <span class="badge bg-danger">Bas</span>
                                    @else
                                        <span class="badge bg-success">OK</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('agent.stocks.show', $stock) }}" class="btn btn-sm btn-info">
                                        <i class="bx bx-show"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    Aucun stock trouvé.<br>
                                    <small>Vérifiez que des stocks sont bien enregistrés et liés à des produits.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $stocks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
@extends('agent.layouts.app')

@section('content')
<div class="row">
    <!-- Statistiques principales -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <div class="avatar-initial bg-label-primary rounded">
                            <i class="bx bx-package bx-sm"></i>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="cardOpt1">
                            <a class="dropdown-item" href="{{ route('agent.collectes.index') }}">Voir détails</a>
                        </div>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Collectes aujourd'hui</span>
                <h3 class="card-title mb-2">{{ $stats['collectes_today'] }}</h3>
                <small class="text-success fw-semibold">
                    <i class="bx bx-up-arrow-alt"></i> +12.4%
                </small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <div class="avatar-initial bg-label-success rounded">
                            <i class="bx bx-trending-up bx-sm"></i>
                        </div>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Poids collecté (kg)</span>
                <h3 class="card-title mb-2">{{ number_format($poids_today, 2) }}</h3>
                <small class="text-success fw-semibold">
                    <i class="bx bx-up-arrow-alt"></i> +8.2%
                </small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <div class="avatar-initial bg-label-info rounded">
                            <i class="bx bx-filter-alt bx-sm"></i>
                        </div>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Quantité triée (kg)</span>
                <h3 class="card-title mb-2">{{ number_format($quantite_triee_today, 2) }}</h3>
                <small class="text-success fw-semibold">
                    <i class="bx bx-up-arrow-alt"></i> +15.3%
                </small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <div class="avatar-initial bg-label-warning rounded">
                            <i class="bx bx-box bx-sm"></i>
                        </div>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Produits fabriqués</span>
                <h3 class="card-title mb-2">{{ $produits_fabriqués_today }}</h3>
                <small class="text-success fw-semibold">
                    <i class="bx bx-up-arrow-alt"></i> +5.7%
                </small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Collectes récentes -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0">Collectes récentes</h5>
                <a href="{{ route('agent.collectes.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body">
                @forelse($collectes_recentes as $collecte)
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-sm me-3">
                        @if($collecte->photo)
                            <img src="{{ asset('storage/' . $collecte->photo) }}" alt="Photo" class="rounded-circle">
                        @else
                            <div class="avatar-initial bg-label-secondary rounded-circle">
                                <i class="bx bx-package"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $collecte->planification->client->nom ?? 'Client inconnu' }}</h6>
                        <small class="text-muted">{{ $collecte->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-label-{{ $collecte->statut === 'terminee' ? 'success' : 'warning' }} badge-status">
                            {{ ucfirst($collecte->statut) }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-muted mb-0">Aucune collecte récente</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Stocks faibles -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0">Alertes stock</h5>
                <a href="{{ route('agent.stocks.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body">
                @forelse($stocks_faibles as $stock)
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-sm me-3">
                        <div class="avatar-initial bg-label-danger rounded-circle">
                            <i class="bx bx-error"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $stock->produit->nom ?? $stock->nom }}</h6>
                        <small class="text-muted">Stock: {{ $stock->quantite_disponible }} {{ $stock->unite_mesure }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-danger badge-status">Faible</span>
                    </div>
                </div>
                @empty
                <p class="text-muted mb-0">Aucun stock faible</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Activités récentes -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0">Activités récentes</h5>
            </div>
            <div class="card-body">
                @forelse($activites as $activite)
                <div class="activity-item">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-3">
                            <div class="avatar-initial bg-label-{{ $activite['type'] === 'pesage' ? 'primary' : 'info' }} rounded-circle">
                                <i class="bx bx-{{ $activite['type'] === 'pesage' ? 'trending-up' : 'filter-alt' }}"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0">{{ $activite['message'] }}</p>
                            <small class="text-muted">{{ $activite['client'] }} • {{ $activite['date']->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted mb-0">Aucune activité récente</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Raccourcis rapides -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0">Actions rapides</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-6 mb-3">
                        <a href="{{ route('agent.pesages.create') }}" class="btn btn-outline-primary w-100">
                            <i class="bx bx-plus me-1"></i>
                            Nouveau pesage
                        </a>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <a href="{{ route('agent.tries.create') }}" class="btn btn-outline-success w-100">
                            <i class="bx bx-filter-alt me-1"></i>
                            Nouveau tri
                        </a>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <a href="{{ route('agent.produits.create') }}" class="btn btn-outline-warning w-100">
                            <i class="bx bx-box me-1"></i>
                            Nouveau produit
                        </a>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <a href="{{ route('agent.rapports.index') }}" class="btn btn-outline-info w-100">
                            <i class="bx bx-bar-chart me-1"></i>
                            Générer rapport
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
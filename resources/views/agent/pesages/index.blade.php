@extends('agent.layouts.app')

@section('content')
<div class="row">
    <!-- Statistiques -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <div class="avatar-initial bg-label-primary rounded">
                            <i class="bx bx-trending-up bx-sm"></i>
                        </div>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Total pesages</span>
                <h3 class="card-title mb-2">{{ $stats['total_pesages'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <div class="avatar-initial bg-label-success rounded">
                            <i class="bx bx-package bx-sm"></i>
                        </div>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Poids total (kg)</span>
                <h3 class="card-title mb-2">{{ number_format($stats['poids_total'], 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <div class="avatar-initial bg-label-info rounded">
                            <i class="bx bx-calendar bx-sm"></i>
                        </div>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Pesages aujourd'hui</span>
                <h3 class="card-title mb-2">{{ $stats['pesages_today'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <div class="avatar-initial bg-label-warning rounded">
                            <i class="bx bx-trending-up bx-sm"></i>
                        </div>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Poids aujourd'hui (kg)</span>
                <h3 class="card-title mb-2">{{ number_format($stats['poids_today'], 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Gestion des pesages</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('agent.pesages.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>
                        Nouveau pesage
                    </a>
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filters">
                        <i class="bx bx-filter-alt me-1"></i>
                        Filtres
                    </button>
                </div>
            </div>

            <!-- Filtres -->
            <div class="collapse" id="filters">
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('agent.pesages.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Date début</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date fin</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Client</label>
                            <input type="text" name="client" class="form-control" placeholder="Nom du client" value="{{ request('client') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="termine" {{ request('statut') === 'termine' ? 'selected' : '' }}>Terminé</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-search me-1"></i>
                                Filtrer
                            </button>
                            <a href="{{ route('agent.pesages.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-reset me-1"></i>
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recherche -->
            <div class="card-body border-bottom">
                <div class="row">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('agent.pesages.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher par description, client..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-primary ms-2">
                                <i class="bx bx-search"></i>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted">{{ $pesages->total() }} pesages trouvés</small>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Poids</th>
                            <th>Unité</th>
                            <th>Description</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesages as $pesage)
                        <tr>
                            <td>{{ $pesage->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $pesage->collecte->planification->client->nom ?? 'N/A' }}</td>
                            <td><strong>{{ $pesage->poids }}</strong></td>
                            <td>{{ $pesage->unite }}</td>
                            <td>{{ $pesage->description ?? '-' }}</td>
                            <td>
                                <span class="badge bg-label-{{ $pesage->statut === 'termine' ? 'success' : 'warning' }}">
                                    {{ ucfirst($pesage->statut) }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('agent.pesages.show', $pesage) }}">
                                            <i class="bx bx-show me-1"></i> Voir
                                        </a>
                                        <a class="dropdown-item" href="{{ route('agent.pesages.edit', $pesage) }}">
                                            <i class="bx bx-edit me-1"></i> Modifier
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('agent.pesages.destroy', $pesage) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce pesage ?')">
                                                <i class="bx bx-trash me-1"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Aucun pesage trouvé
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pesages->hasPages())
            <div class="card-footer">
                {{ $pesages->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
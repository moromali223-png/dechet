@extends('agent.layouts.app')

@section('content')
<div class="container-fluid">

    <!-- ==================== KPI CARDS ==================== -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted">Total Collectes</small>
                            <h3 class="fw-bold mb-0">{{ $collectes->total() }}</h3>
                        </div>
                        <i class="bx bx-package fs-1 text-primary opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted">En attente de pesage</small>
                            <h3 class="fw-bold text-warning mb-0">
                                {{ $collectes->whereNull('pesage')->count() }}
                            </h3>
                        </div>
                        <i class="bx bx-hourglass fs-1 text-warning opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted">Pesées réalisées</small>
                            <h3 class="fw-bold text-success mb-0">
                                {{ $collectes->whereNotNull('pesage')->count() }}
                            </h3>
                        </div>
                        <i class="bx bx-scale fs-1 text-success opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted">Collectes terminées</small>
                            <h3 class="fw-bold text-primary mb-0">
                                {{ $collectes->where('statut', 'terminee')->count() }}
                            </h3>
                        </div>
                        <i class="bx bx-check-circle fs-1 text-primary opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== TABLE PRINCIPALE ==================== -->
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <div>
                <h5 class="mb-1">Collectes Reçues</h5>
                <small class="text-muted">Suivi des collectes assignées à traiter</small>
            </div>
            <button class="btn btn-outline-primary btn-sm" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#filters">
                <i class="bx bx-filter-alt me-1"></i> Filtres
            </button>
        </div>

        <!-- Filtres -->
        <div class="collapse border-bottom" id="filters">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            @foreach($statuts as $key => $label)
                                <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
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
                        <input type="text" name="client" class="form-control" 
                               placeholder="Nom du client" value="{{ request('client') }}">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bx bx-search me-1"></i> Filtrer
                        </button>
                        <a href="{{ route('agent.collectes.index') }}" class="btn btn-outline-secondary">
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recherche -->
        <div class="card-body border-bottom">
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control" 
                       placeholder="Rechercher par client, commentaire ou référence..." 
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-primary ms-2">
                    <i class="bx bx-search"></i>
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Référence</th>
                        <th>Planification</th>
                        <th>Client</th>
                        <th>Zone</th>
                        <th>Statut</th>
                        <th>Pesage</th>
                        <th>Progression</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collectes as $collecte)
                        @php
                            $progress = match($collecte->statut) {
                                'terminee'         => 100,
                                'pesee', 'triee'   => 75,
                                'arrive_au_centre' => 50,
                                'en_cours'         => 30,
                                default            => 15
                            };
                        @endphp
                        <tr>
                            <td>
                                <strong>#COL-{{ str_pad($collecte->id, 5, '0', STR_PAD_LEFT) }}</strong><br>
                                <small class="text-muted">{{ $collecte->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <strong>{{ $collecte->planification?->code_planification ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $collecte->planification?->nom_tournee }}</small>
                            </td>
                            <td>
                                <strong>{{ $collecte->client?->nom ?? 'N/A' }}</strong>
                                @if($collecte->commentaire)
                                    <br><small class="text-muted">{{ Str::limit($collecte->commentaire, 35) }}</small>
                                @endif
                            </td>
                            <td>
                                {{ $collecte->planification?->zone?->nom ?? '<span class="text-muted">-</span>' }}
                            </td>
                            <td>
                                <span class="badge bg-label-{{ getStatusColor($collecte->statut) }}">
                                    {{ ucfirst(str_replace('_', ' ', $collecte->statut)) }}
                                </span>
                            </td>
                            <td>
                                @if($collecte->pesage)
                                    <span class="badge bg-success">
                                        {{ number_format($collecte->pesage->poids, 2) }} kg
                                    </span>
                                @else
                                    <span class="badge bg-warning">En attente</span>
                                @endif
                            </td>
                            <td>
                                <div class="progress" style="height: 7px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $progress }}%"></div>
                                </div>
                                <small class="text-muted">{{ $progress }}%</small>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('agent.collectes.show', $collecte) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    
                                    @if(!$collecte->pesage)
                                        <a href="{{ route('pesages.create', ['collecte' => $collecte->id]) }}" 
                                           class="btn btn-sm btn-success">
                                            <i class="bx bx-weight"></i> Peser
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bx bx-package fs-1 d-block mb-2"></i>
                                Aucune collecte trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer">
            {{ $collectes->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@php
    function getStatusColor($status) {
        return match($status) {
            'en_cours'          => 'warning',
            'terminee'          => 'success',
            'arrive_au_centre'  => 'info',
            'pesee'             => 'primary',
            'triee'             => 'secondary',
            default             => 'secondary'
        };
    }
@endphp
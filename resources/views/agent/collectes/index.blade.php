@extends('agent.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Collectes reçues</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filters">
                        <i class="bx bx-filter-alt me-1"></i>
                        Filtres
                    </button>
                </div>
            </div>

            <!-- Filtres -->
            <div class="collapse" id="filters">
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('agent.collectes.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select">
                                <option value="">Tous les statuts</option>
                                @foreach($statuts as $key => $label)
                                    <option value="{{ $key }}" {{ request('statut') === $key ? 'selected' : '' }}>
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
                            <input type="text" name="client" class="form-control" placeholder="Nom du client" value="{{ request('client') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-search me-1"></i>
                                Filtrer
                            </button>
                            <a href="{{ route('agent.collectes.index') }}" class="btn btn-outline-secondary">
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
                        <form method="GET" action="{{ route('agent.collectes.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher par client, commentaire..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-primary ms-2">
                                <i class="bx bx-search"></i>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted">{{ $collectes->total() }} collectes trouvées</small>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Client</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Pesage</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($collectes as $collecte)
                        <tr>
                            <td>
                                @if($collecte->photo)
                                    <img src="{{ asset('storage/' . $collecte->photo) }}" alt="Photo collecte" class="rounded" width="50" height="50">
                                @else
                                    <div class="avatar avatar-sm">
                                        <div class="avatar-initial bg-label-secondary rounded">
                                            <i class="bx bx-package"></i>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $collecte->planification->client->nom ?? 'N/A' }}</strong>
                                @if($collecte->commentaire)
                                    <br><small class="text-muted">{{ Str::limit($collecte->commentaire, 30) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $this->getStatusColor($collecte->statut) }}">
                                    {{ ucfirst(str_replace('_', ' ', $collecte->statut)) }}
                                </span>
                            </td>
                            <td>{{ $collecte->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($collecte->pesages->count() > 0)
                                    <span class="badge bg-success">{{ $collecte->pesages->sum('poids') }} kg</span>
                                @else
                                    <span class="badge bg-secondary">Non pesé</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('agent.collectes.show', $collecte) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-show"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Aucune collecte trouvée
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($collectes->hasPages())
            <div class="card-footer">
                {{ $collectes->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@php
    function getStatusColor($status) {
        return match($status) {
            'en_cours' => 'warning',
            'terminee' => 'success',
            'arrive_au_centre' => 'info',
            'pesee' => 'primary',
            'triee' => 'secondary',
            default => 'secondary'
        };
    }
@endphp
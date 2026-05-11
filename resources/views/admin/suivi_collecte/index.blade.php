@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">📊 Suivi Général des Collectes</h4>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-success">Effectuées</h6>
                    <h3>{{ $stats['total_effectuees'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Planifiées / En cours</h6>
                    <h3>{{ $stats['total_planifiees'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-primary">Aujourd'hui</h6>
                    <h3>{{ $stats['aujourdhui'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres + Tabs -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="col-md-2">
                    <label class="form-label">Période</label>
                    <select name="date_filter" class="form-select">
                        <option value="">Toutes</option>
                        <option value="today" {{ request('date_filter')=='today' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="week" {{ request('date_filter')=='week' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="month" {{ request('date_filter')=='month' ? 'selected' : '' }}>Ce mois</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Zone</label>
                    <select name="zone_id" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Collecteur</label>
                    <select name="collecteur_id" class="form-select">
                        <option value="">Tous</option>
                        @foreach($collecteurs as $c)
                            <option value="{{ $c->id }}" {{ request('collecteur_id') == $c->id ? 'selected' : '' }}>
                                {{ optional($c->user)->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filtrer</button>
                    <a href="{{ route('suivi_collecte.index') }}" class="btn btn-secondary flex-fill">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a href="?type=effectuees" class="nav-link {{ $type == 'effectuees' ? 'active' : '' }}">✅ Collectes Effectuées</a>
        </li>
        <li class="nav-item">
            <a href="?type=non_effectuees" class="nav-link {{ $type == 'non_effectuees' ? 'active' : '' }}">⏳ Non Effectuées</a>
        </li>
    </ul>

    <!-- Tableau -->
    <div class="card shadow">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Zone</th>
                        <th>Collecteur</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Statut</th>
                        @if($type == 'effectuees')
                            <th>Photo</th>
                        @endif
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collectes as $p)
                        <tr>
                            <td><strong>{{ $p->code_planification ?? '—' }}</strong></td>
                            <td>{{ optional($p->zone)->nom ?? '—' }}</td>
                            <td>{{ optional(optional($p->collecteur)->user)->name ?? '—' }}</td>
                            <td>{{ optional(optional($p->client)->user)->name ?? '—' }}</td>
                            <td>{{ $p->date_prevue?->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $p->statut === 'terminee' ? 'success' : 'warning' }}">
                                    {{ ucfirst(str_replace('_', ' ', $p->statut)) }}
                                </span>
                            </td>
                            @if($type == 'effectuees')
                                <td>
                                    @if($p->collecte?->photo)
                                        <img src="{{ asset('storage/' . $p->collecte->photo) }}" width="55" class="img-thumbnail">
                                    @else
                                        —
                                    @endif
                                </td>
                            @endif
                                        <td>
                                            <a href="{{ route('suivi_collecte.show', $p) }}" class="btn btn-sm btn-outline-info">Détails</a>
                                        </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">Aucune collecte trouvée</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $collectes->links() }}
    </div>
</div>
@endsection
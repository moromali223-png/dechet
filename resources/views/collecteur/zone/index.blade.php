@extends('collecteur.layouts.app')

@section('title', 'Ma Zone - ' . ($zone->nom ?? 'N/A'))

@section('content')
<div class="container-fluid py-4">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-map-marker-alt text-primary"></i> 
            Zone : <strong>{{ $zone->nom ?? 'Non assignée' }}</strong>
        </h4>
        <span class="badge bg-success fs-6">
            {{ $clients->total() }} clients
        </span>
    </div>

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Total Collectes</h6>
                    <h3 class="mb-0">{{ $totalCollectes }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Aujourd'hui</h6>
                    <h3 class="mb-0 text-success">{{ $collectesAujourdhui }}</h3>
                </div>
            </div>
        </div>
        <!-- Ajoute d'autres stats selon tes besoins -->
    </div>

    <!-- Recherche -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Rechercher client (nom, téléphone, adresse...)" 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Rechercher</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des clients -->
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">👥 Mes Clients dans la zone</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th>Dernière collecte</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td>{{ $client->id }}</td>
                                <td>
                                    <strong>{{ $client->user?->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $client->user?->email }}</small>
                                </td>
                                <td>{{ $client->telephone }}</td>
                                <td>
                                    <small>{{ $client->adresse ?? '<span class="text-danger">Non renseignée</span>' }}</small>
                                </td>
                                <td>
                                    @if($client->derniereCollecte)
                                        <span class="text-success">
                                            {{ $client->derniereCollecte->created_at->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-warning">Jamais</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $client->derniereCollecte ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $client->derniereCollecte ? 'Collecté' : 'En attente' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('collecteur.client.show', $client) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Aucun client trouvé dans cette zone.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-3">
                {{ $clients->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Dernières collectes -->
    @if($recentCollectes->isNotEmpty())
    <div class="card border-0 shadow mt-4">
        <div class="card-header bg-white">
            <h5>Dernières collectes réalisées</h5>
        </div>
        <div class="card-body">
            <!-- Liste ou tableau des dernières collectes -->
        </div>
    </div>
    @endif

</div>
@endsection
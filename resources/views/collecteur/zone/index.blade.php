@extends('collecteur.layouts.app')

@section('title', 'Ma Zone')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">

            <div>
                <h4 class="fw-bold mb-1">
                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                    Zone : {{ $zone->nom ?? 'Non assignée' }}
                </h4>

                <small class="text-muted">
                    Liste des clients affectés à votre zone de collecte
                </small>
            </div>

            <div class="text-end">
                <span class="badge bg-primary fs-6 px-3 py-2">
                    {{ $clients->total() }} Clients
                </span>
            </div>

        </div>
    </div>

    {{-- Statistiques --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <i class="fas fa-recycle fa-2x text-success mb-2"></i>

                    <h3 class="fw-bold">
                        {{ $totalCollectes }}
                    </h3>

                    <small class="text-muted">
                        Total collectes
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">

                    <i class="fas fa-calendar-day fa-2x text-info mb-2"></i>

                    <h3 class="fw-bold">
                        {{ $collectesAujourdhui }}
                    </h3>

                    <small class="text-muted">
                        Aujourd'hui
                    </small>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">

                    <i class="fas fa-users fa-2x text-warning mb-2"></i>

                    <h3 class="fw-bold">
                        {{ $clients->total() }}
                    </h3>

                    <small class="text-muted">
                        Clients actifs
                    </small>

                </div>
            </div>
        </div>

    </div>

    {{-- Recherche --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="input-group">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Rechercher un client..."
                        value="{{ request('search') }}"
                    >

                    <button class="btn btn-primary">

                        <i class="fas fa-search"></i>

                        Rechercher

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- Liste des clients --}}
    <div class="row g-4">

        @forelse($clients as $client)

            <div class="col-lg-4 col-md-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        {{-- Informations client --}}
                        <div class="d-flex align-items-center mb-3">

                            <div class="avatar avatar-lg me-3">

                                <span class="avatar-initial rounded-circle bg-primary text-white">

                                    {{ strtoupper(substr($client->name ?? 'C',0,1)) }}

                                </span>

                            </div>

                            <div>

                                <h6 class="mb-0 fw-bold">

                                    {{ $client->name }}

                                </h6>

                                <small class="text-muted">

                                    {{ $client->email }}

                                </small>

                            </div>

                        </div>

                        {{-- Téléphone --}}
                        <div class="mb-2">

                            <small class="text-muted">

                                Téléphone

                            </small>

                            <div class="fw-semibold">

                                {{ $client->telephone ?? '-' }}

                            </div>

                        </div>

                        {{-- Adresse --}}
                        <div class="mb-3">

                            <small class="text-muted">

                                Adresse

                            </small>

                            <div>

                                {{ $client->adresse ?? 'Non renseignée' }}

                            </div>

                        </div>

                        {{-- Dernière collecte --}}
                        <div class="mb-3">

                            <small class="text-muted">

                                Dernière collecte

                            </small>

                            <div>

                                @if($client->derniereCollecte)

                                    <span class="text-success fw-semibold">

                                        {{ $client->derniereCollecte->created_at->format('d/m/Y H:i') }}

                                    </span>

                                @else

                                    <span class="text-warning">

                                        Jamais collecté

                                    </span>

                                @endif

                            </div>

                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between align-items-center">

                            <span class="badge {{ $client->derniereCollecte ? 'bg-success' : 'bg-secondary' }}">

                                {{ $client->derniereCollecte ? 'Collecté' : 'En attente' }}

                            </span>

                            <a
                                href="{{ route('collecteur.client.show', $client) }}"
                                class="btn btn-sm btn-primary rounded-pill"
                            >

                                <i class="fas fa-eye me-1"></i>

                                Voir

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="card shadow-sm border-0">

                    <div class="card-body text-center py-5">

                        <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>

                        <h5 class="fw-bold">

                            Aucun client trouvé

                        </h5>

                        <p class="text-muted mb-0">

                            Aucun client n'est affecté à cette zone.

                        </p>

                    </div>

                </div>

            </div>

        @endforelse

    </div>

    {{-- Pagination --}}
    <div class="mt-4">

        {{ $clients->appends(request()->query())->links() }}

    </div>

</div>
@endsection
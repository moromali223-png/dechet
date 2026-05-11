@extends('layouts.app')

@section('title', 'Gestion des commandes')

@section('content')
<div class="card">
    <div class="card-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
        <div>
            <h4 class="card-title mb-1">Gestion des commandes</h4>
            <p class="text-muted mb-0">Suivez facilement le statut des commandes clients et gérez les actions en un clic.</p>
        </div>
        <div class="text-muted">
            <strong>{{ $commandes->total() }}</strong> commande{{ $commandes->total() > 1 ? 's' : '' }} affichée{{ $commandes->total() > 1 ? 's' : '' }}
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-lg-3 col-sm-6 mb-2">
                <div class="bg-light rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted">En attente</span>
                        <span class="badge bg-warning text-dark">{{ $stats['en_attente'] }}</span>
                    </div>
                    <h5 class="mb-0">{{ $stats['en_attente'] }}</h5>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-2">
                <div class="bg-light rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted">Acceptées</span>
                        <span class="badge bg-success">{{ $stats['acceptee'] }}</span>
                    </div>
                    <h5 class="mb-0">{{ $stats['acceptee'] }}</h5>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-2">
                <div class="bg-light rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted">Refusées</span>
                        <span class="badge bg-danger">{{ $stats['refusee'] }}</span>
                    </div>
                    <h5 class="mb-0">{{ $stats['refusee'] }}</h5>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-2">
                <div class="bg-light rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted">Livrées</span>
                        <span class="badge bg-secondary">{{ $stats['livree'] }}</span>
                    </div>
                    <h5 class="mb-0">{{ $stats['livree'] }}</h5>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code commande</th>
                        <th>Client</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandes as $commande)
                        <tr>
                            <td class="fw-semibold">{{ $commande->code_commande }}</td>
                            <td>{{ $commande->client->user->name ?? 'N/A' }}</td>
                            <td>{{ $commande->produit->nom ?? $commande->produit }}</td>
                            <td>{{ $commande->quantite }}</td>
                            <td>{{ $commande->created_at->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $badgeClass = match ($commande->statut) {
                                        'en_attente' => 'bg-warning text-dark',
                                        'acceptee' => 'bg-success',
                                        'refusee' => 'bg-danger',
                                        'livree' => 'bg-secondary',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} text-capitalize">
                                    @switch($commande->statut)
                                        @case('en_attente')
                                            En attente
                                            @break
                                        @case('acceptee')
                                            Acceptée
                                            @break
                                        @case('refusee')
                                            Refusée
                                            @break
                                        @case('livree')
                                            Livrée
                                            @break
                                        @default
                                            {{ $commande->statut }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="text-end">
                                @if($commande->statut === 'en_attente')
                                    <form method="POST" action="{{ route('commandes.accepter', $commande->id) }}" class="d-inline me-2">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            Accepter
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('commandes.refuser', $commande->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Refuser
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">Aucune action</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Aucune commande trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($commandes->hasPages())
            <div class="mt-4 d-flex justify-content-end">
                {{ $commandes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
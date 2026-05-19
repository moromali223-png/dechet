@extends('layouts.app')

@section('title', 'Gestion des Commandes')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestion des Commandes</h5>
                    <div>
                        <a href="{{ route('admin.paiements.index') }}" class="btn btn-outline-info me-2">
                            <i class="bx bx-credit-card me-1"></i>Paiements
                        </a>
                        <a href="{{ route('admin.produits.index') }}" class="btn btn-outline-primary">
                            <i class="bx bx-package me-1"></i>Produits
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-sm-6 mb-2">
                            <div class="bg-light rounded-3 p-3 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted">Total</span>
                                    <span class="badge bg-primary">{{ $stats['total'] ?? 0 }}</span>
                                </div>
                                <h5 class="mb-0">{{ $stats['total'] ?? 0 }}</h5>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 mb-2">
                            <div class="bg-light rounded-3 p-3 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted">En attente</span>
                                    <span class="badge bg-warning">{{ $stats['en_attente'] ?? 0 }}</span>
                                </div>
                                <h5 class="mb-0">{{ $stats['en_attente'] ?? 0 }}</h5>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 mb-2">
                            <div class="bg-light rounded-3 p-3 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted">Acceptées</span>
                                    <span class="badge bg-success">{{ $stats['acceptee'] ?? 0 }}</span>
                                </div>
                                <h5 class="mb-0">{{ $stats['acceptee'] ?? 0 }}</h5>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 mb-2">
                            <div class="bg-light rounded-3 p-3 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted">Refusées</span>
                                    <span class="badge bg-danger">{{ $stats['refusee'] ?? 0 }}</span>
                                </div>
                                <h5 class="mb-0">{{ $stats['refusee'] ?? 0 }}</h5>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 mb-2">
                            <div class="bg-light rounded-3 p-3 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted">Livrées</span>
                                    <span class="badge bg-secondary">{{ $stats['livree'] ?? 0 }}</span>
                                </div>
                                <h5 class="mb-0">{{ $stats['livree'] ?? 0 }}</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="statutFilter">
                                <option value="">Tous les statuts</option>
                                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="acceptee" {{ request('statut') == 'acceptee' ? 'selected' : '' }}>Acceptée</option>
                                <option value="refusee" {{ request('statut') == 'refusee' ? 'selected' : '' }}>Refusée</option>
                                <option value="livree" {{ request('statut') == 'livree' ? 'selected' : '' }}>Livrée</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tableau -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Code commande</th>
                                    <th>Client</th>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Montant</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commandes as $commande)
                                <tr>
                                    <td class="fw-semibold">{{ $commande->code_commande }}</td>
                                    
                                    <td>
                                        {{ $commande->client?->nom ?? $commande->client?->user?->name ?? 'N/A' }}
                                    </td>
                                    
                                    <!-- Correction principale ici -->
                                    <td>
                                        {{ $commande->produitRelation?->nom ?? $commande->produit ?? 'N/A' }}
                                    </td>
                                    
                                    <td>{{ $commande->quantite }}</td>
                                    
                                    <!-- Montant sécurisé -->
                                    <td>
                                        @php
                                           
                                            $prixUnitaire = $commande->produitRelation?->prix_unitaire ?? 0;
                                            $montant = $commande->montant_total ?? ($commande->quantite * $prixUnitaire);
                                        @endphp
                                        {{ number_format($montant, 2) }} FCFA
                                    </td>
                                    
                                    <td>{{ $commande->created_at?->format('d/m/Y') ?? 'N/A' }}</td>
                                    
                                    <td>
                                        @php
                                            $badgeClass = match ($commande->statut) {
                                                'en_attente' => 'bg-warning text-dark',
                                                'acceptee'   => 'bg-success',
                                                'refusee'    => 'bg-danger',
                                                'livree'     => 'bg-info',
                                                default      => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            @switch($commande->statut)
                                                @case('en_attente') En attente @break
                                                @case('acceptee')   Acceptée   @break
                                                @case('refusee')    Refusée    @break
                                                @case('livree')     Livrée     @break
                                                @default {{ $commande->statut }}
                                            @endswitch
                                        </span>
                                    </td>
                                    
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.commandes.show', $commande) }}">
                                                        <i class="bx bx-show me-1"></i>Voir détail
                                                    </a>
                                                </li>
                                                @if($commande->statut === 'en_attente')
                                                <li>
                                                    <form method="POST" action="{{ route('admin.commandes.accepter', $commande->id) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="bx bx-check me-1"></i>Accepter
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('admin.commandes.refuser', $commande->id) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bx bx-x me-1"></i>Refuser
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bx bx-shopping-bag display-1 text-muted"></i>
                                        <h5 class="mt-3">Aucune commande</h5>
                                    </td>
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
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('statutFilter').addEventListener('change', function () {
    const statut = this.value;
    const url = new URL(window.location);
    if (statut) {
        url.searchParams.set('statut', statut);
    } else {
        url.searchParams.delete('statut');
    }
    window.location.href = url.toString();
});
</script>
@endpush

@endsection
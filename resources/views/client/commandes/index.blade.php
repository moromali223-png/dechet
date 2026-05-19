@extends('layouts.app')

@section('title', 'Mes Commandes')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes Commandes</h5>
                    <a href="{{ route('client.produits.index') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>Nouvelle Commande
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Code Commande</th>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                           <tbody>
    @forelse($commandes as $commande)
    <tr>
        <td>{{ $commande->code_commande }}</td>

        <td>
            {{ $commande->produitRelation?->nom ?? $commande->produit ?? 'Produit supprimé' }}
        </td>

        <td>{{ $commande->quantite }}</td>

        <td>
            {{ number_format(
                $commande->montant_total ??
                ($commande->quantite * ($commande->produitRelation?->prix_unitaire ?? 0)),
                2
            ) }} FCFA
        </td>

        <td>
            @switch($commande->statut)
                @case('en_attente')
                    <span class="badge bg-warning">En attente</span>
                    @break

                @case('acceptee')
                    <span class="badge bg-success">Acceptée</span>
                    @break

                @case('refusee')
                    <span class="badge bg-danger">Refusée</span>
                    @break

                @case('livree')
                    <span class="badge bg-primary">Livrée</span>
                    @break

                @default
                    <span class="badge bg-secondary">{{ $commande->statut }}</span>
            @endswitch
        </td>

        <td>{{ $commande->date_commande?->format('d/m/Y') }}</td>

        <td>
            <a href="{{ route('client.commandes.show', $commande) }}" class="btn btn-sm btn-info">
                Voir
            </a>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="text-center text-muted">
            Aucune commande trouvée.
        </td>
    </tr>
    @endforelse
</tbody>
                        </table>
                    </div>

                    {{ $commandes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
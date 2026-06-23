@extends('layouts.app')

@section('title', 'Détail Commande')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Commande {{ $commande->code_commande }}</h5>
                    <a href="{{ route('admin.commandes.index') }}" class="btn btn-outline-primary">
                        <i class="bx bx-arrow-back me-1"></i>Retour
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informations de la commande</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Code:</strong></td>
                                    <td>{{ $commande->code_commande }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Client:</strong></td>
                                    <td>{{ $commande->client->nom ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date:</strong></td>
                                    <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Statut:</strong></td>
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
                                                <span class="badge bg-info">Livrée</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                            </table>

                            @if($commande->statut === 'en_attente')
                            <div class="mt-3">
                                <form action="{{ route('admin.commandes.accepter', $commande) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Accepter cette commande ?')">
                                        <i class="bx bx-check me-1"></i>Accepter
                                    </button>
                                </form>
                                <form action="{{ route('admin.commandes.refuser', $commande) }}" method="POST" class="d-inline ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Refuser cette commande ?')">
                                        <i class="bx bx-x me-1"></i>Refuser
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Produit commandé</h6>
                            <div class="d-flex">
                                @if($commande->produitRelation?->photo)
                                <img src="{{ asset('storage/' . $commande->produitRelation->photo) }}" class="me-3" style="width: 80px; height: 80px; object-fit: cover;" alt="{{ $commande->produitRelation->nom }}">
                                @endif
                                <div>
                                    <h6>{{ $commande->produitRelation?->nom ?? $commande->produit }}</h6>
                                    <p class="mb-1">{{ $commande->produitRelation?->description }}</p>
                                    <p class="mb-0"><strong>Quantité:</strong> {{ $commande->quantite }}</p>
                                    <p class="mb-0"><strong>Prix unitaire:</strong> {{ number_format($commande->produitRelation?->prix_unitaire ?? 0, 2) }} FCFA</p>
                                    <p class="mb-0"><strong>Total:</strong> {{ number_format($commande->montant_total, 2) }} FCFA</p>
                                    <p class="mb-0"><strong>Stock disponible:</strong> {{ $commande->produitRelation?->stock?->quantite_disponible ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($commande->paiements->count() > 0)
                    <div class="mt-4">
                        <h6>Paiements associés</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Référence</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commande->paiements as $paiement)
                                    <tr>
                                        <td>{{ $paiement->reference_paiement }}</td>
                                        <td>{{ number_format($paiement->montant, 2) }} FCFA</td>
                                        <td>
                                            @switch($paiement->statut)
                                                @case('en_attente')
                                                    <span class="badge bg-warning">En attente</span>
                                                    @break
                                                @case('valide')
                                                    <span class="badge bg-success">Payé</span>
                                                    @break
                                                @case('annule')
                                                    <span class="badge bg-danger">Annulé</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $paiement->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
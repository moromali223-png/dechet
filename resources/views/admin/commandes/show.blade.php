@extends('layouts.app')

@section('title', 'Détail de la Commande')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Commande {{ $commande->code_commande }}</h5>
                    <a href="{{ route('admin.commandes.index') }}" class="btn btn-outline-primary">
                        <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                    </a>
                </div>
                
                <div class="card-body">
                    <div class="row g-4">

                        <!-- Informations de la commande -->
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Informations de la commande</h6>
                            <table class="table table-bordered table-sm">
                                <tr>
                                    <td width="40%"><strong>Code Commande</strong></td>
                                    <td><strong>{{ $commande->code_commande }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Client</strong></td>
                                    <td>{{ $commande->user?->name ?? 'Client supprimé' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date</strong></td>
                                    <td>{{ $commande->date_commande?->format('d/m/Y à H:i') ?? $commande->created_at?->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Statut</strong></td>
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
                                                <span class="badge bg-secondary">{{ ucfirst($commande->statut) }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                            </table>

                            @if($commande->statut === 'en_attente')
                            <div class="mt-4">
                                <form action="{{ route('admin.commandes.accepter', $commande) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Voulez-vous vraiment accepter cette commande ?')">
                                        <i class="bx bx-check me-1"></i>Accepter la commande
                                    </button>
                                </form>
                                <form action="{{ route('admin.commandes.refuser', $commande) }}" method="POST" class="d-inline ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment refuser cette commande ?')">
                                        <i class="bx bx-x me-1"></i>Refuser la commande
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>

                        <!-- Produit commandé -->
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Produit commandé</h6>
                            <div class="card bg-light border-0 p-3">
                                <div class="d-flex">
                                    @if($commande->produit?->photo)
                                        <img 
                                            src="{{ asset('storage/' . $commande->produit->photo) }}" 
                                            class="me-3 rounded" 
                                            style="width: 100px; height: 100px; object-fit: cover;" 
                                            alt="{{ $commande->produit->nom }}">
                                    @else
                                        <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                            <i class="bx bx-package text-white" style="font-size: 2.5rem;"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <h6 class="mb-2">{{ $commande->produit?->nom ?? 'Produit supprimé' }}</h6>
                                        <p class="text-muted mb-2">{{ $commande->produit?->description ?? 'Aucune description' }}</p>
                                        
                                        <p><strong>Quantité :</strong> {{ $commande->quantite }} {{ $commande->produit?->unite_mesure ?? '' }}</p>
                                        <p><strong>Prix unitaire :</strong> {{ number_format($commande->produit?->prix_unitaire ?? $commande->prix_unitaire ?? 0, 2) }} FCFA</p>
                                        <p class="text-primary fw-bold"><strong>Total :</strong> {{ number_format($commande->montant_total, 2) }} FCFA</p>
                                        <p><strong>Stock disponible :</strong> {{ $commande->produit?->stock?->quantite_disponible ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Paiements associés -->
                    @if($commande->paiements->count() > 0)
                    <div class="mt-5">
                        <h6 class="fw-bold mb-3">Paiements associés</h6>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-light">
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
                                        <td>{{ $paiement->reference_paiement ?? 'N/A' }}</td>
                                        <td>{{ number_format($paiement->montant, 2) }} FCFA</td>
                                        <td>
                                            @switch($paiement->statut)
                                                @case('en_attente')
                                                    <span class="badge bg-warning">En attente</span>
                                                    @break
                                                @case('valide')
                                                @case('paye')
                                                    <span class="badge bg-success">Payé</span>
                                                    @break
                                                @case('annule')
                                                    <span class="badge bg-danger">Annulé</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $paiement->statut }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $paiement->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info mt-4">
                        Aucun paiement enregistré pour cette commande.
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
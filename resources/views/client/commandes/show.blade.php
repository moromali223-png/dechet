@extends('layouts.app')

@section('title', 'Détail de la Commande')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Commande {{ $commande->code_commande }}</h5>

                    <a href="{{ route('client.commandes.index') }}" class="btn btn-outline-primary">
                        <i class="bx bx-arrow-back me-1"></i>Retour aux commandes
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
                                    <td><strong>Date de commande</strong></td>
                                    <td>{{ $commande->date_commande?->format('d/m/Y à H:i') ?? $commande->created_at->format('d/m/Y H:i') }}</td>
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
                                <tr>
                                    <td><strong>Quantité</strong></td>
                                    <td>{{ $commande->quantite }} {{ $commande->produit?->unite_mesure ?? 'unité' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Produit commandé -->
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Produit commandé</h6>

                            <div class="card border-0 bg-light p-3">
                                <div class="d-flex align-items-start">

                                    @if($commande->produit?->photo)
                                        <img
                                            src="{{ asset('storage/' . $commande->produit->photo) }}"
                                            class="rounded me-3"
                                            style="width: 110px; height: 110px; object-fit: cover;"
                                            alt="{{ $commande->produit->nom }}">
                                    @else
                                        <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 110px; height: 110px;">
                                            <i class="bx bx-package text-white" style="font-size: 2.5rem;"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <h6 class="mb-2">{{ $commande->produit?->nom ?? 'Produit supprimé' }}</h6>

                                        <p class="text-muted mb-2">
                                            {{ $commande->produit?->description ?? 'Aucune description disponible.' }}
                                        </p>

                                        <p class="mb-1">
                                            <strong>Prix unitaire :</strong> 
                                            {{ number_format($commande->produit?->prix_unitaire ?? $commande->prix_unitaire ?? 0, 2) }} FCFA
                                        </p>

                                        <p class="mb-0 text-primary fw-bold fs-5">
                                            Total : {{ number_format($commande->montant_total, 2) }} FCFA
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Historique des paiements -->
                    @if($commande->paiements->count() > 0)
                    <div class="mt-5">
                        <h6 class="fw-bold mb-3">Historique des paiements</h6>

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
                        Aucun paiement enregistré pour cette commande pour le moment.
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
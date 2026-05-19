@extends('layouts.app')

@section('title', 'Détail Commande')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Commande {{ $commande->code_commande }}</h5>

                    <a href="{{ route('client.commandes.index') }}" class="btn btn-outline-primary">
                        <i class="bx bx-arrow-back me-1"></i>Retour
                    </a>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Informations de la commande</h6>

                            <table class="table table-bordered table-sm">
                                <tr>
                                    <td><strong>Code</strong></td>
                                    <td>{{ $commande->code_commande }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Date</strong></td>
                                    <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
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
                                                <span class="badge bg-info">Livrée</span>
                                                @break

                                            @default
                                                <span class="badge bg-secondary">{{ $commande->statut }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Produit commandé</h6>

                            <div class="card border-0 bg-light p-3">
                                <div class="d-flex">

                                    @if($commande->produitRelation && $commande->produitRelation->photo)
                                        <img
                                            src="{{ asset('storage/' . $commande->produitRelation->photo) }}"
                                            class="rounded me-3"
                                            style="width: 100px; height: 100px; object-fit: cover;"
                                            alt="{{ $commande->produitRelation->nom }}">
                                    @endif

                                    <div>
                                        <h6 class="mb-2">
                                            {{ $commande->produitRelation?->nom ?? $commande->produit }}
                                        </h6>

                                        <p class="text-muted mb-1">
                                            {{ $commande->produitRelation?->description ?? 'Aucune description' }}
                                        </p>

                                        <p class="mb-1">
                                            <strong>Quantité :</strong>
                                            {{ $commande->quantite }}
                                        </p>

                                        <p class="mb-1">
                                            <strong>Prix unitaire :</strong>
                                            {{ number_format($commande->produitRelation?->prix_unitaire ?? 0, 2) }} FCFA
                                        </p>

                                        <p class="mb-0 text-primary fw-bold">
                                            <strong>Total :</strong>
                                            {{ number_format($commande->montant_total, 2) }} FCFA
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    @if($commande->paiements->count())
                    <div class="mt-5">
                        <h6 class="fw-bold mb-3">Historique paiements</h6>

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
                                        <td>{{ $paiement->reference_paiement }}</td>

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
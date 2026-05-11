@extends('layouts.app')

@section('title', 'Détails du Produit')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>Détails du Produit : {{ $produit->nom }}</h4>
            <a href="{{ route('produits.index') }}" class="btn btn-secondary">Retour à la liste</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Nom</th>
                            <td>{{ $produit->nom }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>{{ $produit->type }}</td>
                        </tr>
                        <tr>
                            <th>Unité de mesure</th>
                            <td>{{ $produit->unite_mesure }}</td>
                        </tr>
                        <tr>
                            <th>Prix unitaire</th>
                            <td>{{ number_format($produit->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>
                                @if($produit->statut == 'actif')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>Tri Associé</th>
                            <td>{{ $produit->trie->type_dechet ?? 'Non défini' }}</td>
                        </tr>
                        <tr>
                            <th>Stock Actuel</th>
                            <td>
                                @if($produit->stock)
                                    <strong>{{ number_format($produit->stock->quantite_disponible, 2) }} {{ $produit->stock->unite_mesure }}</strong>
                                @else
                                    <span class="text-danger">Aucun stock</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Date de création</th>
                            <td>{{ $produit->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($produit->description)
                <div class="mt-4">
                    <h5>Description</h5>
                    <p class="border p-3 bg-light">{{ $produit->description }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
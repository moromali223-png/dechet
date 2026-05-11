@extends('layouts.app')

@section('title', 'Détail Stock')

@section('content')
<div class="container-fluid">

    {{-- Info Produit --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h3>{{ $stock->produit->nom ?? 'Produit inconnu' }}</h3>

            <p>
                Quantité :
                <strong>{{ $stock->quantite_disponible }}</strong>
            </p>

            <p>
                Prix unitaire :
                <strong>{{ number_format($stock->prix_unitaire, 0, ',', ' ') }} FCFA</strong>
            </p>

            <p>
                Valeur totale :
                <strong>
                    {{ number_format($stock->quantite_disponible * $stock->prix_unitaire, 0, ',', ' ') }} FCFA
                </strong>
            </p>

            @if($stock->quantite_disponible <= $stock->seuil_alerte)
                <span class="badge bg-warning">Stock faible</span>
            @else
                <span class="badge bg-success">Stock OK</span>
            @endif
        </div>
    </div>

    {{-- Historique --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h5>Historique des mouvements</h5>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Quantité</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($historique as $mouvement)
                        <tr>
                            <td>{{ $mouvement->created_at }}</td>
                            <td>{{ $mouvement->type ?? 'N/A' }}</td>
                            <td>{{ $mouvement->quantite ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
@extends('layouts.app')

@section('title', 'Détails Stock')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <div>

            <h4 class="fw-bold mb-1">
                Détails du stock
            </h4>

            <small class="text-muted">
                Informations détaillées du stock
            </small>

        </div>

        <a href="{{ route('inventaire.index') }}"
           class="btn btn-outline-secondary btn-sm">

            Retour

        </a>

    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-6">

                <label class="text-muted small">
                    Produit
                </label>

                <div class="fw-semibold">
                    {{ $stock->produit->nom ?? '-' }}
                </div>

            </div>


            <div class="col-md-6">

                <label class="text-muted small">
                    Code stock
                </label>

                <div class="fw-semibold">
                    {{ $stock->code_stock }}
                </div>

            </div>


            <div class="col-md-6">

                <label class="text-muted small">
                    Quantité disponible
                </label>

                <div class="fw-semibold">
                    {{ number_format($stock->quantite_disponible, 2, ',', ' ') }}
                    {{ $stock->unite_mesure }}
                </div>

            </div>


            <div class="col-md-6">

                <label class="text-muted small">
                    Prix unitaire
                </label>

                <div class="fw-semibold">
                    {{ number_format($stock->prix_unitaire, 0, ',', ' ') }} FCFA
                </div>

            </div>


            <div class="col-md-6">

                <label class="text-muted small">
                    Valeur totale
                </label>

                <div class="fw-bold">
                    {{ number_format($stock->valeur_totale, 0, ',', ' ') }} FCFA
                </div>

            </div>


            <div class="col-md-6">

                <label class="text-muted small">
                    Seuil alerte
                </label>

                <div class="fw-semibold">
                    {{ $stock->seuil_alerte }}
                </div>

            </div>

        </div>

    </div>

</div>

@endsection
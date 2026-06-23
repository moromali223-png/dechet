@extends('layouts.app')

@section('title', 'Modifier Stock')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <h4 class="fw-bold mb-1">
            Modifier le stock
        </h4>

        <small class="text-muted">
            Ajustement et correction du stock
        </small>

    </div>

    <div class="card-body">

        <form action="{{ route('inventaire.update', $stock->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="row g-3">

                {{-- PRODUIT --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Produit
                    </label>

                    <input type="text"
                           class="form-control"
                           value="{{ $stock->produit->nom ?? '' }}"
                           disabled>

                </div>


                {{-- CODE --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Code stock
                    </label>

                    <input type="text"
                           class="form-control"
                           value="{{ $stock->code_stock }}"
                           disabled>

                </div>


                {{-- QUANTITÉ --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Quantité disponible
                    </label>

                    <input type="number"
                           step="0.01"
                           name="quantite_disponible"
                           value="{{ $stock->quantite_disponible }}"
                           class="form-control"
                           required>

                </div>


                {{-- PRIX --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Prix unitaire
                    </label>

                    <input type="number"
                           step="0.01"
                           name="prix_unitaire"
                           value="{{ $stock->prix_unitaire }}"
                           class="form-control"
                           required>

                </div>


                {{-- UNITÉ --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Unité mesure
                    </label>

                    <input type="text"
                           name="unite_mesure"
                           value="{{ $stock->unite_mesure }}"
                           class="form-control"
                           required>

                </div>


                {{-- SEUIL --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Seuil alerte
                    </label>

                    <input type="number"
                           step="0.01"
                           name="seuil_alerte"
                           value="{{ $stock->seuil_alerte }}"
                           class="form-control"
                           required>

                </div>

            </div>


            <div class="mt-4 d-flex justify-content-end gap-2">

                <a href="{{ route('inventaire.index') }}"
                   class="btn btn-light">

                    Retour

                </a>

                <button type="submit"
                        class="btn btn-warning">

                    Mettre à jour

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@extends('layouts.app')

@section('title', 'Créer Produit')

@section('content')

<div class="container-fluid">

    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                Création Produit Fini
            </h4>
        </div>

        <div class="card-body">

            <form action="{{ route('agent.produits.store') }}" method="POST">

                @csrf

                <div class="row">

                   

                    <!-- NOM PRODUIT -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Nom du Produit 
                        </label>

                        <select name="nom"
                                class="form-select"
                                required>

                            <option value="">
                                -- Sélectionner --
                            </option>

                            <!-- PLASTIQUE -->
                            <optgroup label="Plastique">
                                <option>Pavé plastique</option>
                                <option>Chaise recyclée</option>
                                <option>Seau recyclé</option>
                            </optgroup>

                            <!-- METAL -->
                            <optgroup label="Métal">
                                <option>Fer recyclé</option>
                                <option>Barre métallique</option>
                            </optgroup>

                            <!-- PAPIER -->
                            <optgroup label="Papier / Carton">
                                <option>Cahier recyclé</option>
                                <option>Carton recyclé</option>
                            </optgroup>

                            <!-- VERRE -->
                            <optgroup label="Verre">
                                <option>Bouteille recyclée</option>
                            </optgroup>

                        </select>

                    </div>

                    <!-- QUANTITE -->
                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">
                            Quantité Produite
                        </label>

                        <input type="number"
                               step="0.01"
                               name="quantite"
                               class="form-control"
                               required>

                    </div>
                    <!-- PRIX -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Prix Unitaire
                        </label>

                        <input type="number"
                               step="0.01"
                               name="prix_unitaire"
                               class="form-control">

                    </div>

                    <!-- UNITE -->
                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">
                            Unité
                        </label>

                        <select name="unite_mesure"
                                class="form-select">

                            <option value="kg">kg</option>
                            <option value="T">Tonne</option>
                            <option value="pièce">Pièce</option>

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">
    <label class="form-label">
        Type de produit
    </label>

    <select name="type" class="form-select" required>
        <option value="">-- Sélectionner --</option>

        <option value="Produit recyclé">
            Produit recyclé
        </option>

        <option value="Produit transformé">
            Produit transformé
        </option>

        <option value="Produit fini">
            Produit fini
        </option>
    </select>
</div>

                     <!-- TRI -->
                    <div class="col-md-12 mb-4">

                        <label class="form-label fw-bold">
                            Matière Première (Tri)
                        </label>

                        <select name="trie_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Sélectionner un tri --
                            </option>

                            @foreach($tries as $tri)

                                <option value="{{ $tri->id }}">

                                    TRI #{{ $tri->id }}
                                    |
                                    {{ $tri->type_dechet }}
                                    |
                                    Qté :
                                    {{ $tri->quantite_trier }} {{ $tri->unite }}
                                    |
                                    Qualité :
                                    {{ $tri->qualite }}
                                    |
                                    Date :
                                    {{ $tri->created_at->format('d/m/Y') }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- DESCRIPTION -->
                    <div class="col-md-12 mb-3">

                        <label class="form-label fw-bold">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control"></textarea>

                    </div>

                </div>

                <div class="d-flex justify-content-between mt-4">

                    <a href="{{ route('agent.produits.index') }}"
                       class="btn btn-secondary">

                        Retour

                    </a>

                    <button class="btn btn-success">

                        Enregistrer Produit

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
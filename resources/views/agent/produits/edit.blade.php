@extends('layouts.app')

@section('title', 'Modifier Produit')

@section('content')

<div class="container-fluid">

    <div class="card shadow-lg border-0">

        <div class="card-header bg-warning">

            <h4 class="mb-0">
                Modifier Produit Fini
            </h4>

        </div>

        <div class="card-body">

            <form action="{{ route('agent.produits.update', $produit->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    <!-- TRI -->
                    <div class="col-md-12 mb-4">

                        <label class="form-label fw-bold">
                            Matière Première utilisée
                        </label>

                        <select name="trie_id"
                                class="form-select"
                                required>

                            @foreach($tries as $tri)

                                <option value="{{ $tri->id }}"
                                    {{ $produit->trie_id == $tri->id ? 'selected' : '' }}>

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

                    <!-- PRODUIT -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Produit Fini
                        </label>

                        <select name="nom"
                                class="form-select"
                                required>

                            <optgroup label="Plastique">
                                <option value="Pavé plastique"
                                    {{ $produit->nom == 'Pavé plastique' ? 'selected' : '' }}>
                                    Pavé plastique
                                </option>

                                <option value="Chaise recyclée"
                                    {{ $produit->nom == 'Chaise recyclée' ? 'selected' : '' }}>
                                    Chaise recyclée
                                </option>
                            </optgroup>

                            <optgroup label="Métal">
                                <option value="Fer recyclé"
                                    {{ $produit->nom == 'Fer recyclé' ? 'selected' : '' }}>
                                    Fer recyclé
                                </option>
                            </optgroup>

                            <optgroup label="Papier">
                                <option value="Cahier recyclé"
                                    {{ $produit->nom == 'Cahier recyclé' ? 'selected' : '' }}>
                                    Cahier recyclé
                                </option>
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
                               value="{{ $produit->quantite }}"
                               required>

                    </div>

                    <!-- UNITE -->
                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">
                            Unité
                        </label>

                        <select name="unite_mesure"
                                class="form-select">

                            <option value="kg"
                                {{ $produit->unite_mesure == 'kg' ? 'selected' : '' }}>
                                kg
                            </option>

                            <option value="T"
                                {{ $produit->unite_mesure == 'T' ? 'selected' : '' }}>
                                Tonne
                            </option>

                            <option value="pièce"
                                {{ $produit->unite_mesure == 'pièce' ? 'selected' : '' }}>
                                Pièce
                            </option>

                        </select>

                    </div>

                    <!-- PRIX -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Prix Unitaire
                        </label>

                        <input type="number"
                               step="0.01"
                               name="prix_unitaire"
                               class="form-control"
                               value="{{ $produit->prix_unitaire }}">

                    </div>

                    <!-- STATUT -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Statut
                        </label>

                        <select name="statut"
                                class="form-select">

                            <option value="en_production"
                                {{ $produit->statut == 'en_production' ? 'selected' : '' }}>
                                En production
                            </option>

                            <option value="termine"
                                {{ $produit->statut == 'termine' ? 'selected' : '' }}>
                                Terminé
                            </option>

                            <option value="stocke"
                                {{ $produit->statut == 'stocke' ? 'selected' : '' }}>
                                Stocké
                            </option>

                        </select>

                    </div>

                    <!-- DESCRIPTION -->
                    <div class="col-md-12 mb-3">

                        <label class="form-label fw-bold">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control">{{ $produit->description }}</textarea>

                    </div>

                </div>

                <div class="d-flex justify-content-between mt-4">

                    <a href="{{ route('agent.produits.index') }}"
                       class="btn btn-secondary">

                        Retour

                    </a>

                    <button class="btn btn-primary">

                        Mettre à jour

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
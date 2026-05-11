@extends('layouts.app')

@section('title', 'Ajouter un Produit')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Ajouter un Produit
            </h3>

            <p class="text-muted mb-0">
                Création et enregistrement d’un nouveau produit recyclé
            </p>

        </div>

        <a href="{{ route('produits.index') }}"
           class="btn btn-light border shadow-sm">

            <i class="bx bx-arrow-back"></i>
            Retour

        </a>

    </div>

    <!-- CARD -->
    <div class="card border-0 shadow-sm">

        <!-- HEADER -->
        <div class="card-header bg-primary text-white py-3">

            <h5 class="mb-0">

                <i class="bx bx-package me-2"></i>

                Informations du Produit

            </h5>

        </div>

        <div class="card-body p-4">

            <!-- ERRORS -->
            @if ($errors->any())

                <div class="alert alert-danger border-0 shadow-sm">

                    <div class="fw-bold mb-2">

                        <i class="bx bx-error-circle me-1"></i>

                        Veuillez corriger les erreurs suivantes :

                    </div>

                    <ul class="mb-0">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <!-- FORM -->
            <form action="{{ route('produits.store') }}" method="POST">

                @csrf

                <div class="row">

                    <!-- NOM -->
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

                    <!-- TYPE -->
                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-semibold">

                            Type de Produit

                        </label>

                        <select name="type"
                                class="form-select"
                                required>

                            <option value="">
                                -- Sélectionner --
                            </option>

                            <option value="Produit recyclé"
                                {{ old('type') == 'Produit recyclé' ? 'selected' : '' }}>

                                Produit recyclé

                            </option>

                            <option value="Produit transformé"
                                {{ old('type') == 'Produit transformé' ? 'selected' : '' }}>

                                Produit transformé

                            </option>

                            <option value="Produit fini"
                                {{ old('type') == 'Produit fini' ? 'selected' : '' }}>

                                Produit fini

                            </option>

                        </select>

                    </div>

                    <!-- QUANTITE -->
                    <div class="col-md-4 mb-4">

                        <label class="form-label fw-semibold">

                            Quantité

                        </label>

                        <input type="number"
                               step="0.01"
                               name="quantite"
                               class="form-control"
                               placeholder="0.00"
                               value="{{ old('quantite') }}"
                               required>

                    </div>

                    <!-- UNITE -->
                    <div class="col-md-4 mb-4">

                        <label class="form-label fw-semibold">

                            Unité de Mesure

                        </label>

                        <select name="unite_mesure"
                                class="form-select"
                                required>

                            <option value="kg"
                                {{ old('unite_mesure') == 'kg' ? 'selected' : '' }}>

                                Kilogramme (kg)

                            </option>

                            <option value="tonne"
                                {{ old('unite_mesure') == 'tonne' ? 'selected' : '' }}>

                                Tonne

                            </option>

                            <option value="litre"
                                {{ old('unite_mesure') == 'litre' ? 'selected' : '' }}>

                                Litre

                            </option>

                            <option value="piece"
                                {{ old('unite_mesure') == 'piece' ? 'selected' : '' }}>

                                Pièce

                            </option>

                        </select>

                    </div>

                    <!-- PRIX -->
                    <div class="col-md-4 mb-4">

                        <label class="form-label fw-semibold">

                            Prix Unitaire

                        </label>

                        <div class="input-group">

                            <input type="number"
                                   step="0.01"
                                   name="prix_unitaire"
                                   class="form-control"
                                   placeholder="0"
                                   value="{{ old('prix_unitaire') }}"
                                   required>

                            <span class="input-group-text">

                                FCFA

                            </span>

                        </div>

                    </div>

                    <!-- STATUT -->
                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-semibold">

                            Statut

                        </label>

                        <select name="statut"
                                class="form-select"
                                required>

                            <option value="actif"
                                {{ old('statut') == 'actif' ? 'selected' : '' }}>

                                Actif

                            </option>

                            <option value="inactif"
                                {{ old('statut') == 'inactif' ? 'selected' : '' }}>

                                Inactif

                            </option>

                        </select>

                    </div>

                    <!-- TRI -->
                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-semibold">

                            Tri Associé

                        </label>

                        <select name="trie_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Sélectionner un tri --
                            </option>

                            @foreach($tries as $trie)

                                <option value="{{ $trie->id }}"
                                    {{ old('trie_id') == $trie->id ? 'selected' : '' }}>

                                    {{ $trie->type_dechet }}
                                    |
                                    {{ $trie->quantite_trier }} {{ $trie->unite }}
                                    |
                                    {{ $trie->qualite }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- DESCRIPTION -->
                    <div class="col-md-12 mb-4">

                        <label class="form-label fw-semibold">

                            Description

                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control"
                                  placeholder="Description du produit...">{{ old('description') }}</textarea>

                    </div>

                </div>

                <!-- ACTIONS -->
                <div class="d-flex justify-content-end gap-2 mt-3">

                    <a href="{{ route('produits.index') }}"
                       class="btn btn-light border">

                        Annuler

                    </a>

                    <button type="submit"
                            class="btn btn-success shadow-sm">

                        <i class="bx bx-save"></i>

                        Enregistrer le Produit

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
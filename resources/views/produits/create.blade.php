@extends('layouts.app')

@section('title', 'Ajouter un Produit')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-success text-white rounded-top-4 py-3">
            <h4 class="mb-0">
                <i class="bx bx-package me-2"></i>
                Ajouter un nouveau produit
            </h4>
        </div>

        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('produits.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf

                <div class="row g-4">

                    <!-- PHOTO -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Photo du produit</label>
                        <div class="text-center border rounded-4 p-3 bg-light">
                            <img id="photo-preview"
                                 src="https://via.placeholder.com/280x220?text=Photo+Produit"
                                 class="img-fluid rounded shadow-sm mb-3"
                                 style="height:220px; object-fit:cover; width:100%;">
                            <input type="file" 
                                   name="photo" 
                                   id="photo" 
                                   class="form-control"
                                   accept="image/*">
                        </div>
                    </div>

                    <!-- FORMULAIRE -->
                    <div class="col-md-8">
                        <div class="row g-3">

                            <!-- NOM DU PRODUIT -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nom du Produit</label>
                                <select name="nom" class="form-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    <optgroup label="Plastique">
                                        <option value="Pavé plastique">Pavé plastique</option>
                                        <option value="Chaise recyclée">Chaise recyclée</option>
                                        <option value="Seau recyclé">Seau recyclé</option>
                                    </optgroup>
                                    <optgroup label="Métal">
                                        <option value="Fer recyclé">Fer recyclé</option>
                                        <option value="Barre métallique">Barre métallique</option>
                                    </optgroup>
                                    <optgroup label="Papier / Carton">
                                        <option value="Cahier recyclé">Cahier recyclé</option>
                                        <option value="Carton recyclé">Carton recyclé</option>
                                    </optgroup>
                                    <optgroup label="Verre">
                                        <option value="Bouteille recyclée">Bouteille recyclée</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- TYPE DE PRODUIT -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Type de Produit</label>
                                <select name="type" class="form-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="Produit recyclé" {{ old('type') == 'Produit recyclé' ? 'selected' : '' }}>
                                        Produit recyclé
                                    </option>
                                    <option value="Produit transformé" {{ old('type') == 'Produit transformé' ? 'selected' : '' }}>
                                        Produit transformé
                                    </option>
                                    <option value="Produit fini" {{ old('type') == 'Produit fini' ? 'selected' : '' }}>
                                        Produit fini
                                    </option>
                                </select>
                            </div>

                         
                            <!-- <div class="col-md-4">
                                <label class="form-label fw-bold">Quantité</label>
                                <input type="number" 
                                       step="0.01" 
                                       name="quantite" 
                                       class="form-control" 
                                       value="{{ old('quantite') }}" 
                                       required>
                            </div> -->

                            <!-- UNITÉ -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Unité de Mesure</label>
                                <select name="unite_mesure" class="form-select" required>
                                    <option value="kg" {{ old('unite_mesure') == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                                    <option value="tonne" {{ old('unite_mesure') == 'tonne' ? 'selected' : '' }}>Tonne</option>
                                    <option value="litre" {{ old('unite_mesure') == 'litre' ? 'selected' : '' }}>Litre</option>
                                    <option value="piece" {{ old('unite_mesure') == 'piece' ? 'selected' : '' }}>Pièce</option>
                                </select>
                            </div>

                            <!-- PRIX -->
                            <div class="col-md-6     ">
                                <label class="form-label fw-bold">Prix Unitaire</label>
                                <div class="input-group">
                                    <input type="number" 
                                           step="0.01" 
                                           name="prix_unitaire" 
                                           class="form-control" 
                                           value="{{ old('prix_unitaire') }}" 
                                           required>
                                    <span class="input-group-text">FCFA</span>
                                </div>
                            </div>

                            <!-- STATUT -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Statut</label>
                                <select name="statut" class="form-select">
                                    <option value="actif" {{ old('statut', 'actif') == 'actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>

                           
                          

                            <!-- DESCRIPTION -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" 
                                          rows="4" 
                                          class="form-control"
                                          placeholder="Description détaillée du produit...">{{ old('description') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- BOUTONS -->
                <div class="text-end mt-4">
                    <a href="{{ route('produits.index') }}" class="btn btn-light border me-2">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bx bx-save me-1"></i>
                        Enregistrer le Produit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview de l'image
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('photo-preview').src = URL.createObjectURL(file);
        }
    });
</script>
@endsection
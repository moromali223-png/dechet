@extends('layouts.app')

@section('title', 'Créer Produit')

@section('content')
<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <!-- HEADER MODERNE -->
        <div class="card-header bg-gradient bg-primary text-white py-3">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="bx bx-package me-2"></i>
                    Création Produit Fini
                </h4>
            </div>
        </div>

        <div class="card-body p-4">

            <!-- ERREURS -->
            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <strong>Erreur :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('agent.produits.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="row g-4">

                    <!-- LEFT: PHOTO -->
                    <div class="col-lg-4">

                        <label class="form-label fw-bold">Photo du produit</label>

                        <div class="border rounded-4 p-3 bg-light text-center shadow-sm">

                            <img id="photo-preview"
                                 src="https://via.placeholder.com/300x240?text=Produit"
                                 class="img-fluid rounded-3 mb-3"
                                 style="height:240px; object-fit:cover; width:100%;">

                            <input type="file"
                                   name="photo"
                                   id="photo"
                                   class="form-control form-control-sm"
                                   accept="image/*">
                        </div>

                        <small class="text-muted d-block mt-2">
                            Formats: JPG, PNG, WEBP (max 2MB)
                        </small>
                    </div>

                    <!-- RIGHT: FORM -->
                    <div class="col-lg-8">

                        <div class="row g-3">

                            <!-- NOM -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nom du produit</label>
                                <select name="nom" class="form-select shadow-sm" required>
                                    <option value="">-- Sélectionner --</option>

                                    <optgroup label="Plastique">
                                        <option>Pavé plastique</option>
                                        <option>Chaise recyclée</option>
                                        <option>Seau recyclé</option>
                                    </optgroup>

                                    <optgroup label="Métal">
                                        <option>Fer recyclé</option>
                                        <option>Barre métallique</option>
                                    </optgroup>

                                    <optgroup label="Papier / Carton">
                                        <option>Cahier recyclé</option>
                                        <option>Carton recyclé</option>
                                    </optgroup>

                                    <optgroup label="Verre">
                                        <option>Bouteille recyclée</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- TYPE -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Type</label>
                                <select name="type" class="form-select shadow-sm" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option>Produit recyclé</option>
                                    <option>Produit transformé</option>
                                    <option>Produit fini</option>
                                </select>
                            </div>

                            <!-- UNITE -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Unité</label>
                                <select name="unite_mesure" class="form-select shadow-sm">
                                    <option value="kg">Kg</option>
                                    <option value="tonne">Tonne</option>
                                    <option value="piece">Pièce</option>
                                </select>
                            </div>

                            <!-- PRIX -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Prix unitaire</label>
                                <div class="input-group shadow-sm">
                                    <input type="number"
                                           step="0.01"
                                           name="prix_unitaire"
                                           class="form-control"
                                           placeholder="0">
                                    <span class="input-group-text">FCFA</span>
                                </div>
                            </div>

                            <!-- STATUT -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Statut</label>
                                <select name="statut" class="form-select shadow-sm">
                                    <option value="actif">Actif</option>
                                    <option value="inactif">Inactif</option>
                                </select>
                            </div>

                            <!-- DESCRIPTION -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description"
                                          rows="4"
                                          class="form-control shadow-sm"
                                          placeholder="Décrivez le produit..."></textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">

                    <a href="{{ route('agent.produits.index') }}"
                       class="btn btn-light border px-4">
                        ← Retour
                    </a>

                    <button type="submit"
                            class="btn btn-success px-5 shadow-sm">
                        ✓ Enregistrer
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<script>
document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('photo-preview').src = URL.createObjectURL(file);
    }
});
</script>

@endsection
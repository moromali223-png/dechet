@extends('layouts.app')

@section('title', 'Modifier le Produit')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-warning text-dark rounded-top-4 py-3">
            <h4 class="mb-0">
                <i class="bx bx-edit-alt me-2"></i>
                Modifier le Produit : <strong>{{ $produit->nom }}</strong>
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

            <form action="{{ route('produits.update', $produit) }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    <!-- PHOTO -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Photo du produit</label>
                        <div class="text-center border rounded-4 p-3 bg-light">
                            <img id="photo-preview"
                                 src="{{ $produit->photo ? asset('storage/' . $produit->photo) : 'https://via.placeholder.com/280x220?text=Photo+Produit' }}"
                                 class="img-fluid rounded shadow-sm mb-3"
                                 style="height:220px; object-fit:cover; width:100%;">

                            <input type="file" 
                                   name="photo" 
                                   id="photo" 
                                   class="form-control"
                                   accept="image/*">
                            <small class="text-muted d-block mt-2">Laisser vide pour conserver la photo actuelle</small>
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
                                    
                                    <!-- Option actuelle si elle n'existe pas dans la liste -->
                                    @if($produit->nom)
                                        <option value="{{ $produit->nom }}" selected>
                                            {{ $produit->nom }} (actuel)
                                        </option>
                                    @endif

                                    <optgroup label="Plastique">
                                        <option value="Pavé plastique" {{ old('nom', $produit->nom) == 'Pavé plastique' ? 'selected' : '' }}>Pavé plastique</option>
                                        <option value="Chaise recyclée" {{ old('nom', $produit->nom) == 'Chaise recyclée' ? 'selected' : '' }}>Chaise recyclée</option>
                                        <option value="Seau recyclé" {{ old('nom', $produit->nom) == 'Seau recyclé' ? 'selected' : '' }}>Seau recyclé</option>
                                    </optgroup>
                                    <optgroup label="Métal">
                                        <option value="Fer recyclé" {{ old('nom', $produit->nom) == 'Fer recyclé' ? 'selected' : '' }}>Fer recyclé</option>
                                        <option value="Barre métallique" {{ old('nom', $produit->nom) == 'Barre métallique' ? 'selected' : '' }}>Barre métallique</option>
                                    </optgroup>
                                    <optgroup label="Papier / Carton">
                                        <option value="Cahier recyclé" {{ old('nom', $produit->nom) == 'Cahier recyclé' ? 'selected' : '' }}>Cahier recyclé</option>
                                        <option value="Carton recyclé" {{ old('nom', $produit->nom) == 'Carton recyclé' ? 'selected' : '' }}>Carton recyclé</option>
                                    </optgroup>
                                    <optgroup label="Verre">
                                        <option value="Bouteille recyclée" {{ old('nom', $produit->nom) == 'Bouteille recyclée' ? 'selected' : '' }}>Bouteille recyclée</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- TYPE DE PRODUIT -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Type de Produit</label>
                                <select name="type" class="form-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="Produit recyclé" {{ old('type', $produit->type) == 'Produit recyclé' ? 'selected' : '' }}>Produit recyclé</option>
                                    <option value="Produit transformé" {{ old('type', $produit->type) == 'Produit transformé' ? 'selected' : '' }}>Produit transformé</option>
                                    <option value="Produit fini" {{ old('type', $produit->type) == 'Produit fini' ? 'selected' : '' }}>Produit fini</option>
                                </select>
                            </div>

                            <!-- Autres champs -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Quantité</label>
                                <input type="number" step="0.01" name="quantite" 
                                       class="form-control" 
                                       value="{{ old('quantite', $produit->quantite) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Unité de Mesure</label>
                                <select name="unite_mesure" class="form-select" required>
                                    <option value="kg" {{ old('unite_mesure', $produit->unite_mesure) == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                                    <option value="tonne" {{ old('unite_mesure', $produit->unite_mesure) == 'tonne' ? 'selected' : '' }}>Tonne</option>
                                    <option value="litre" {{ old('unite_mesure', $produit->unite_mesure) == 'litre' ? 'selected' : '' }}>Litre</option>
                                    <option value="piece" {{ old('unite_mesure', $produit->unite_mesure) == 'piece' ? 'selected' : '' }}>Pièce</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Prix Unitaire</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="prix_unitaire" 
                                           class="form-control" 
                                           value="{{ old('prix_unitaire', $produit->prix_unitaire) }}" required>
                                    <span class="input-group-text">FCFA</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Statut</label>
                                <select name="statut" class="form-select">
                                    <option value="actif" {{ old('statut', $produit->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactif" {{ old('statut', $produit->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tri Associé</label>
                                <select name="trie_id" class="form-select" required>
                                    <option value="">-- Sélectionner un tri --</option>
                                    @foreach($tries as $trie)
                                        <option value="{{ $trie->id }}" 
                                            {{ old('trie_id', $produit->trie_id) == $trie->id ? 'selected' : '' }}>
                                            {{ $trie->type_dechet }} | {{ $trie->quantite_trier ?? '' }} {{ $trie->unite ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" rows="4" class="form-control">{{ old('description', $produit->description) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                 <div class="text-end mt-4">
                    <a href="{{ route('produits.index') }}" class="btn btn-light border me-2">Annuler</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bx bx-save me-1"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview photo
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('photo-preview').src = URL.createObjectURL(file);
        }
    });
</script>
@endsection
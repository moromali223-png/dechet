@extends('layouts.app')

@section('title', 'Modifier le Produit')

@section('content')
<div class="container">
    <h1>Modifier le Produit</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produits.update', $produit->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom">Nom du produit</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $produit->nom) }}" required>
        </div>

        <div class="mb-3">
            <label for="type">Type</label>
            <input type="text" name="type" id="type" class="form-control" value="{{ old('type', $produit->type) }}" required>
        </div>

        <div class="mb-3">
            <label for="unite_mesure">Unité de mesure</label>
            <select name="unite_mesure" id="unite_mesure" class="form-control" required>
                <option value="kg" {{ old('unite_mesure', $produit->unite_mesure) == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                <option value="tonne" {{ old('unite_mesure', $produit->unite_mesure) == 'tonne' ? 'selected' : '' }}>Tonne (t)</option>
                <option value="litre" {{ old('unite_mesure', $produit->unite_mesure) == 'litre' ? 'selected' : '' }}>Litre (L)</option>
                <option value="piece" {{ old('unite_mesure', $produit->unite_mesure) == 'piece' ? 'selected' : '' }}>Pièce</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="quantite">Quantité</label>
            <input type="number" step="0.01" name="quantite" id="quantite" class="form-control" value="{{ old('quantite', $produit->quantite) }}" required>
        </div>

        <div class="mb-3">
            <label for="prix_unitaire">Prix unitaire (FCFA)</label>
            <input type="number" step="0.01" name="prix_unitaire" id="prix_unitaire" class="form-control" value="{{ old('prix_unitaire', $produit->prix_unitaire) }}" required>
        </div>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $produit->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="statut">Statut</label>
            <select name="statut" id="statut" class="form-control" required>
                <option value="actif" {{ old('statut', $produit->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                <option value="inactif" {{ old('statut', $produit->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="trie_id">Tri associé</label>
            <select name="trie_id" id="trie_id" class="form-control" required>
                <option value="">Sélectionner un tri</option>
                @foreach($tries as $trie)
                    <option value="{{ $trie->id }}" {{ old('trie_id', $produit->trie_id) == $trie->id ? 'selected' : '' }}>
                        {{ $trie->type_dechet }} ({{ $trie->quantite_trier }} {{ $trie->unite }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour le produit</button>
        <a href="{{ route('produits.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
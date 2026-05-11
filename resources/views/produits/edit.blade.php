@extends('layouts.app')

@section('title', 'Modifier le Produit')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Modifier le Produit : {{ $produit->nom }}</h4>
        </div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('produits.update', $produit) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom du produit <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" value="{{ old('nom', $produit->nom) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <input type="text" name="type" class="form-control" value="{{ old('type', $produit->type) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Unité de mesure <span class="text-danger">*</span></label>
                        <select name="unite_mesure" class="form-control" required>
                            <option value="kg" {{ old('unite_mesure', $produit->unite_mesure) == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                            <option value="tonne" {{ old('unite_mesure', $produit->unite_mesure) == 'tonne' ? 'selected' : '' }}>Tonne</option>
                            <option value="litre" {{ old('unite_mesure', $produit->unite_mesure) == 'litre' ? 'selected' : '' }}>Litre (L)</option>
                            <option value="piece" {{ old('unite_mesure', $produit->unite_mesure) == 'piece' ? 'selected' : '' }}>Pièce</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Prix unitaire (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="prix_unitaire" class="form-control" 
                               value="{{ old('prix_unitaire', $produit->prix_unitaire) }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Statut <span class="text-danger">*</span></label>
                        <select name="statut" class="form-control" required>
                            <option value="actif" {{ old('statut', $produit->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="inactif" {{ old('statut', $produit->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $produit->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tri associé <span class="text-danger">*</span></label>
                    <select name="trie_id" class="form-control" required>
                        <option value="">-- Sélectionner un tri --</option>
                        @foreach($tries as $trie)
                            <option value="{{ $trie->id }}" {{ old('trie_id', $produit->trie_id) == $trie->id ? 'selected' : '' }}>
                                {{ $trie->type_dechet ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="{{ route('produits.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
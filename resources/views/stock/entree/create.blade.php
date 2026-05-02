@extends('layouts.app')

@section('title', 'Entrée de Stock')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h4 class="card-title mb-1">Nouvelle Entrée de Stock</h4>
            <small class="text-muted">Ajouter des produits au stock existant</small>
        </div>
        <div>
            <a href="{{ route('inventaire.index') }}" class="btn btn-outline-secondary btn-sm">Voir l'inventaire</a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('stock-entree.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="stock_id" class="form-label">Produit en Stock <span class="text-danger">*</span></label>
                    <select name="stock_id" id="stock_id" class="form-select" required onchange="updateUnit()">
                        <option value="">Sélectionner un produit</option>
                        @foreach($stocks as $stock)
                            <option value="{{ $stock->id }}" data-unite="{{ $stock->unite_mesure }}">
                                {{ $stock->nom }}
                                @if($stock->produit)
                                    ({{ $stock->produit->nom }})
                                @endif
                                - Stock actuel: {{ number_format($stock->quantite_disponible, 2) }} {{ $stock->unite_mesure }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="quantite" class="form-label">Quantité à ajouter <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0.01" name="quantite" id="quantite"
                               class="form-control" value="{{ old('quantite') }}" required>
                        <span class="input-group-text" id="unite-label">unités</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="source" class="form-label">Source de l'entrée <span class="text-danger">*</span></label>
                    <select name="source" id="source" class="form-select" required onchange="toggleClientField()">
                        <option value="">Sélectionner une source</option>
                        <option value="Source du produit" {{ old('source') == 'Source du produit' ? 'selected' : '' }}>Source du produit (Production)</option>
                        <option value="Retour client" {{ old('source') == 'Retour client' ? 'selected' : '' }}>Retour client ↩️</option>
                        <option value="Retour de stock" {{ old('source') == 'Retour de stock' ? 'selected' : '' }}>Retour de stock (Interne)</option>
                        <option value="Fournisseur" {{ old('source') == 'Fournisseur' ? 'selected' : '' }}>Fournisseur</option>
                        <option value="Ajustement inventaire" {{ old('source') == 'Ajustement inventaire' ? 'selected' : '' }}>Ajustement inventaire</option>
                        <option value="Autre" {{ old('source') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <div class="col-md-6" id="client_field" style="display: none;">
                    <label for="client_nom" class="form-label">Nom du client / Référence retour</label>
                    <input type="text" name="client_nom" id="client_nom" class="form-control" placeholder="Ex: Client Martin ou Facture #123">
                </div>

                <div class="col-md-6">
                    <label for="description" class="form-label">Description (optionnel)</label>
                    <input type="text" name="description" id="description"
                           class="form-control" value="{{ old('description') }}"
                           placeholder="Détails supplémentaires...">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Enregistrer l'entrée
                </button>
                <a href="{{ route('inventaire.index') }}" class="btn btn-secondary ms-2">
                    <i class="bx bx-arrow-back me-1"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
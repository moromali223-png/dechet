@extends('layouts.app')

@section('title', 'Ajouter Stock')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex align-items-center">
            <i class="bx bx-package fs-4 me-2 text-primary"></i>
            <div>
                <h4 class="mb-0 fw-bold">Nouvelle Entrée en Stock</h4>
                <small class="text-muted">Enregistrement d'un produit dans l'inventaire</small>
            </div>
        </div>
    </div>

    <div class="card-body">
        {{-- Alertes --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('inventaire.store') }}" method="POST" id="stockForm">
            @csrf

            <div class="row g-4">

                <!-- ==================== PRODUIT ==================== -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Produit <span class="text-danger">*</span></label>
                    <select name="produit_id" id="produit_id" 
                            class="form-select form-select-lg @error('produit_id') is-invalid @enderror" 
                            required>
                        <option value="">-- Sélectionner un produit --</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->id }}"
                                    data-prix="{{ $produit->prix_unitaire ?? 0 }}"
                                    data-unite="{{ $produit->unite_mesure ?? '' }}"
                                    {{ old('produit_id') == $produit->id ? 'selected' : '' }}>
                                {{ $produit->nom }}
                                @if($produit->prix_unitaire)
                                    — {{ number_format($produit->prix_unitaire, 2) }} FCFA
                                @endif
                                @if($produit->unite_mesure)
                                    ({{ $produit->unite_mesure }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('produit_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- ==================== TRIE / CENTRE DE TRI ==================== -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Centre de Tri / Destination</label>
                    <select name="trie_id" id="trie_id"
                            class="form-select form-select-lg @error('trie_id') is-invalid @enderror">
                        <option value="">-- Aucun / Non spécifié --</option>
                        @foreach($tries as $trie)
                            <option value="{{ $trie->id }}"
                                    {{ old('trie_id') == $trie->id ? 'selected' : '' }}>
                                #{{ $trie->id }} — 
                                {{ $trie->type_dechet }}
                                @if($trie->qualite)
                                    ({{ $trie->qualite }})
                                @endif
                                — {{ $trie->quantite_trier ?? 0 }} {{ $trie->unite ?? '' }}
                                @if($trie->destination)
                                    → {{ $trie->destination }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('trie_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Prix Unitaire -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Prix Unitaire (FCFA) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" 
                               step="0.01" 
                               name="prix_unitaire" 
                               id="prix_unitaire"
                               value="{{ old('prix_unitaire') }}"
                               class="form-control @error('prix_unitaire') is-invalid @enderror" 
                               required>
                        <span class="input-group-text">FCFA</span>
                    </div>
                    @error('prix_unitaire')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Unité de Mesure -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Unité de Mesure <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="unite_mesure" 
                           id="unite_mesure"
                           value="{{ old('unite_mesure') }}"
                           class="form-control @error('unite_mesure') is-invalid @enderror" 
                           placeholder="kg, pièce, tonne..." 
                           required>
                    @error('unite_mesure')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Quantité -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Quantité Initiale <span class="text-danger">*</span></label>
                    <input type="number" 
                           step="0.01" 
                           name="quantite_disponible" 
                           value="{{ old('quantite_disponible') }}"
                           class="form-control @error('quantite_disponible') is-invalid @enderror" 
                           required min="0.01">
                    @error('quantite_disponible')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Seuil d'Alerte -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Seuil d'Alerte</label>
                    <input type="number" 
                           step="0.01" 
                           name="seuil_alerte" 
                           value="{{ old('seuil_alerte', 10) }}"
                           class="form-control @error('seuil_alerte') is-invalid @enderror">
                    @error('seuil_alerte')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="mt-5 d-flex justify-content-end gap-3">
                <a href="{{ route('inventaire.index') }}" class="btn btn-light px-4">
                    <i class="bx bx-x"></i> Annuler
                </a>
                <button type="submit" class="btn btn-success px-5">
                    <i class="bx bx-save"></i> Enregistrer en Stock
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('produit_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (selectedOption.value) {
        // Auto-remplissage
        document.getElementById('prix_unitaire').value = selectedOption.dataset.prix || '';
        document.getElementById('unite_mesure').value = selectedOption.dataset.unite || '';
    } else {
        document.getElementById('prix_unitaire').value = '';
        document.getElementById('unite_mesure').value = '';
    }
});

// Déclencher l'événement au chargement si une valeur est déjà sélectionnée (old input)
window.addEventListener('load', function() {
    const produitSelect = document.getElementById('produit_id');
    if (produitSelect.value) {
        produitSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
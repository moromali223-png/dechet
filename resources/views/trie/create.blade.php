@extends('layouts.app')

@section('title', 'Nouveau Tri')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ajouter un Tri</h5>
            <a href="{{ route('tries.index') }}" class="btn btn-outline-secondary">← Retour à la liste</a>
        </div>

        <div class="card-body">
            <form action="{{ route('tries.store') }}" method="POST">
                @csrf

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <!-- Type de Déchet -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Type de Déchet <span class="text-danger">*</span></label>
                        <select name="type_dechet" class="form-select @error('type_dechet') is-invalid @enderror" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="Plastique" {{ old('type_dechet') == 'Plastique' ? 'selected' : '' }}>Plastique</option>
                            <option value="Métal" {{ old('type_dechet') == 'Métal' ? 'selected' : '' }}>Métal</option>
                            <option value="Papier/Carton" {{ old('type_dechet') == 'Papier/Carton' ? 'selected' : '' }}>Papier / Carton</option>
                            <option value="Verre" {{ old('type_dechet') == 'Verre' ? 'selected' : '' }}>Verre</option>
                            <option value="Organique" {{ old('type_dechet') == 'Organique' ? 'selected' : '' }}>Organique</option>
                            <option value="Autre" {{ old('type_dechet') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type_dechet') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Quantité -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Quantité <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="quantite_trier" 
                               class="form-control @error('quantite_trier') is-invalid @enderror" 
                               value="{{ old('quantite_trier') }}" required>
                        @error('quantite_trier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Unité (Select) -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Unité <span class="text-danger">*</span></label>
                        <select name="unite" class="form-select @error('unite') is-invalid @enderror" required>
                            <option value="kg" {{ old('unite', 'kg') == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                            <option value="g" {{ old('unite') == 'g' ? 'selected' : '' }}>Gramme (g)</option>
                            <option value="T" {{ old('unite') == 'T' ? 'selected' : '' }}>Tonne (T)</option>
                            <option value="L" {{ old('unite') == 'L' ? 'selected' : '' }}>Litre (L)</option>
                        </select>
                        @error('unite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Pesage associé -->
                <div class="mb-3">
                    <label class="form-label">Pesage associé <span class="text-danger">*</span></label>
                    <select name="pesage_id" class="form-select @error('pesage_id') is-invalid @enderror" required>
                        <option value="">-- Sélectionner un pesage --</option>
                        @foreach($pesages as $pesage)
                            <option value="{{ $pesage->id }}" {{ old('pesage_id') == $pesage->id ? 'selected' : '' }}>
                                Pesage #{{ $pesage->id }} — 
                                {{ number_format($pesage->poids, 2) }} {{ $pesage->unite ?? 'kg' }}
                            </option>
                        @endforeach
                    </select>
                    @error('pesage_id') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                <!-- Qualité du Tri -->
                <div class="mb-3">
                    <label class="form-label">Qualité du Tri <span class="text-danger">*</span></label>
                    <select name="qualite" class="form-select @error('qualite') is-invalid @enderror" required>
                        <option value="Excellent" {{ old('qualite') == 'Excellent' ? 'selected' : '' }}>Excellent</option>
                        <option value="Bon" {{ old('qualite', 'Bon') == 'Bon' ? 'selected' : '' }}>Bon</option>
                        <option value="Moyen" {{ old('qualite') == 'Moyen' ? 'selected' : '' }}>Moyen</option>
                        <option value="Mauvais" {{ old('qualite') == 'Mauvais' ? 'selected' : '' }}>Mauvais</option>
                    </select>
                    @error('qualite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Destination -->
                <div class="mb-3">
                    <label class="form-label">Destination</label>
                    <select name="destination" class="form-select @error('destination') is-invalid @enderror">
                        <option value="">-- Sélectionner --</option>
                        <option value="Recyclé" {{ old('destination') == 'Recyclé' ? 'selected' : '' }}>Recyclé</option>
                        <option value="Revendu" {{ old('destination') == 'Revendu' ? 'selected' : '' }}>Revendu</option>
                        <option value="Stocké" {{ old('destination') == 'Stocké' ? 'selected' : '' }}>Stocké</option>
                        <option value="Déchet final" {{ old('destination') == 'Déchet final' ? 'selected' : '' }}>Déchet final</option>
                    </select>
                </div>

                <!-- Valeur Estimée -->
                <div class="mb-3">
                    <label class="form-label">Valeur Estimée (FCFA)</label>
                    <input type="number" step="0.01" name="valeur_estimee" 
                           class="form-control @error('valeur_estimee') is-invalid @enderror" 
                           value="{{ old('valeur_estimee') }}">
                    @error('valeur_estimee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Notes -->
                <div class="mb-3">
                    <label class="form-label">Notes / Observations</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                              rows="4">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Enregistrer le Tri</button>
                    <a href="{{ route('tries.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
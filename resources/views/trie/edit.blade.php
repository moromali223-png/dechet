@extends('layouts.app')

@section('title', 'Modifier un Tri')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Modifier le Tri #{{ $tri->id }}</h5>
            <a href="{{ route('tries.show', $tri) }}" class="btn btn-secondary">Annuler</a>
        </div>

        <div class="card-body">
            <form action="{{ route('tries.update', $tri) }}" method="POST">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pesage <span class="text-danger">*</span></label>
                        <select name="pesage_id" class="form-select @error('pesage_id') is-invalid @enderror" required>
                            <option value="">Sélectionner un pesage</option>
                            @foreach($pesages as $pesage)
                                <option value="{{ $pesage->id }}" {{ old('pesage_id', $tri->pesage_id) == $pesage->id ? 'selected' : '' }}>
                                    {{ $pesage->id }} - {{ $pesage->date_pesage }}
                                </option>
                            @endforeach
                        </select>
                        @error('pesage_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Type de Déchet <span class="text-danger">*</span></label>
                        <input type="text" name="type_dechet" class="form-control @error('type_dechet') is-invalid @enderror"
                               value="{{ old('type_dechet', $tri->type_dechet) }}" required>
                        @error('type_dechet') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Quantité triée <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="quantite_trier" class="form-control @error('quantite_trier') is-invalid @enderror"
                               value="{{ old('quantite_trier', $tri->quantite_trier) }}" required>
                        @error('quantite_trier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Unité <span class="text-danger">*</span></label>
                        <select name="unite" class="form-select @error('unite') is-invalid @enderror" required>
                            <option value="kg" {{ old('unite', $tri->unite) == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="g" {{ old('unite', $tri->unite) == 'g' ? 'selected' : '' }}>g</option>
                            <option value="T" {{ old('unite', $tri->unite) == 'T' ? 'selected' : '' }}>T</option>
                            <option value="L" {{ old('unite', $tri->unite) == 'L' ? 'selected' : '' }}>L</option>
                        </select>
                        @error('unite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Qualité <span class="text-danger">*</span></label>
                        <select name="qualite" class="form-select @error('qualite') is-invalid @enderror" required>
                            <option value="Excellent" {{ old('qualite', $tri->qualite) == 'Excellent' ? 'selected' : '' }}>Excellent</option>
                            <option value="Bon" {{ old('qualite', $tri->qualite) == 'Bon' ? 'selected' : '' }}>Bon</option>
                            <option value="Moyen" {{ old('qualite', $tri->qualite) == 'Moyen' ? 'selected' : '' }}>Moyen</option>
                            <option value="Mauvais" {{ old('qualite', $tri->qualite) == 'Mauvais' ? 'selected' : '' }}>Mauvais</option>
                        </select>
                        @error('qualite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Destination</label>
                        <select name="destination" class="form-select @error('destination') is-invalid @enderror">
                            <option value="">Sélectionner une destination</option>
                            <option value="Recyclé" {{ old('destination', $tri->destination) == 'Recyclé' ? 'selected' : '' }}>Recyclé</option>
                            <option value="Revendu" {{ old('destination', $tri->destination) == 'Revendu' ? 'selected' : '' }}>Revendu</option>
                            <option value="Stocké" {{ old('destination', $tri->destination) == 'Stocké' ? 'selected' : '' }}>Stocké</option>
                            <option value="Déchet final" {{ old('destination', $tri->destination) == 'Déchet final' ? 'selected' : '' }}>Déchet final</option>
                        </select>
                        @error('destination') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Valeur estimée (€)</label>
                        <input type="number" step="0.01" name="valeur_estimee" class="form-control @error('valeur_estimee') is-invalid @enderror"
                               value="{{ old('valeur_estimee', $tri->valeur_estimee) }}">
                        @error('valeur_estimee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $tri->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('tries.show', $tri) }}" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ajouter un Pesage</h5>
            <a href="{{ route('pesages.index') }}" class="btn btn-outline-secondary btn-sm">
                ← Retour à la liste
            </a>
        </div>
        
        <div class="card-body">
            <form action="{{ route('pesages.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Collecte <span class="text-danger">*</span></label>
                    <select name="id_collecte" class="form-select @error('id_collecte') is-invalid @enderror" required>
                        <option value="">-- Sélectionner une collecte --</option>
                        @foreach($collectes as $collecte)
                            <option value="{{ $collecte->id }}" {{ old('id_collecte') == $collecte->id ? 'selected' : '' }}>
                                Collecte #{{ $collecte->id }} - 
                                {{ $collecte->created_at->format('d/m/Y à H:i') }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_collecte') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Poids (kg) <span class="text-danger">*</span></label>
                        <input type="number" 
                               step="0.01" 
                               name="poids" 
                               class="form-control @error('poids') is-invalid @enderror" 
                               placeholder="0.00" 
                               value="{{ old('poids') }}" 
                               required />
                        @error('poids') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unité <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="unite" 
                               class="form-control @error('unite') is-invalid @enderror" 
                               value="{{ old('unite', 'kg') }}" 
                               required />
                        @error('unite') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Statut <span class="text-danger">*</span></label>
                    <select name="statut" class="form-select @error('statut') is-invalid @enderror" required>
                        <option value="Validé" {{ old('statut') == 'Validé' ? 'selected' : '' }}>Validé</option>
                        <option value="En attente" {{ old('statut') == 'En attente' ? 'selected' : '' }}>En attente</option>
                        <option value="Rejeté" {{ old('statut') == 'Rejeté' ? 'selected' : '' }}>Rejeté</option>
                    </select>
                    @error('statut') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" 
                              class="form-control @error('description') is-invalid @enderror" 
                              rows="4">{{ old('description') }}</textarea>
                    @error('description') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Enregistrer le Pesage</button>
                    <a href="{{ route('pesages.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ajouter un Pesage</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pesages.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Collecte</label>
                    <select name="id_collecte" class="form-select @error('id_collecte') is-invalid @enderror" required>
                        <option value="">Sélectionner la collecte</option>
                        @foreach($collectes as $collecte)
                            <option value="{{ $collecte->id }}">Collecte #{{ $collecte->id }} - {{ $collecte->created_at }}</option>
                        @endforeach
                    </select>
                    @error('id_collecte') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Poids</label>
                        <input type="number" step="0.01" name="poids" class="form-control @error('poids') is-invalid @enderror" placeholder="0.00" value="{{ old('poids') }}" required />
                        @error('poids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unité</label>
                        <input type="text" name="unite" class="form-control" value="kg" required />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="Validé">Validé</option>
                        <option value="En attente">En attente</option>
                        <option value="Rejeté">Rejeté</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('pesages.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
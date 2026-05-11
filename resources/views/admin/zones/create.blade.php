@extends('layouts.app')

@section('content')
<h2>Ajouter une zone</h2>

<form action="{{ route('zones.store') }}" method="POST">
    @csrf
    
    <div class="mb-3">
        <label>Nom zone *</label>
        <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
        @error('nom') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    
    <div class="mb-3">
        <label>Ville *</label>
        <input type="text" name="ville" class="form-control" value="{{ old('ville') }}" required>
        @error('ville') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        @error('description') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="{{ route('zones.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
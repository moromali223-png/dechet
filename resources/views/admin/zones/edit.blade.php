@extends('layouts.app')

@section('content')
<h2>Modifier la zone</h2>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('zones.update', $zone->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="mb-3">
        <label>Nom zone *</label>
        <input type="text" name="nom" class="form-control" value="{{ old('nom', $zone->nom) }}" required>
        @error('nom') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    
    <div class="mb-3">
        <label>Ville *</label>
        <input type="text" name="ville" class="form-control" value="{{ old('ville', $zone->ville) }}" required>
        @error('ville') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control">{{ old('description', $zone->description) }}</textarea>
        @error('description') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="{{ route('zones.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection

@extends('layouts.app')

@section('title', 'Modifier la déclaration')

@section('content')
<div class="container">
    <h1>Modifier la déclaration</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('declarations.update', $declaration) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="type_dechet" class="form-label">Type de déchet</label>
            <input type="text" id="type_dechet" name="type_dechet" class="form-control" value="{{ old('type_dechet', $declaration->type_dechet) }}" required>
        </div>

        <div class="mb-3">
            <label for="poids_estime" class="form-label">Poids estimé (kg)</label>
            <input type="number" id="poids_estime" name="poids_estime" class="form-control" value="{{ old('poids_estime', $declaration->poids_estime) }}" step="0.01">
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" id="photo" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
            @if($declaration->photo)
                <small class="text-muted">Photo actuelle : <a href="{{ asset('storage/' . $declaration->photo) }}" target="_blank">Voir</a></small>
            @endif
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $declaration->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('declarations.show', $declaration) }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection

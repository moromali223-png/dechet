@extends('layouts.app')

@section('content')
<h2>Détails de la zone</h2>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ $zone->nom }}</h5>
        <p class="card-text"><strong>ID:</strong> {{ $zone->id }}</p>
        <p class="card-text"><strong>Ville:</strong> {{ $zone->ville }}</p>
        <p class="card-text"><strong>Description:</strong> {{ $zone->description ?? 'Aucune description' }}</p>
        <p class="card-text"><strong>Créée le:</strong> {{ $zone->created_at->format('d/m/Y H:i') }}</p>
        <p class="card-text"><strong>Mise à jour le:</strong> {{ $zone->updated_at->format('d/m/Y H:i') }}</p>
    </div>
</div>

<a href="{{ route('zones.index') }}" class="btn btn-secondary mt-3">Retour à la liste</a>
<a href="{{ route('zones.edit', $zone->id) }}" class="btn btn-warning mt-3">Modifier</a>
@endsection
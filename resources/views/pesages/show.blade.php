@extends('layouts.app')

@section('title', 'Détail du pesage')

@section('content')
<div class="container">

    <h1>Détail du pesage</h1>

    <div class="card p-3">
        <p><strong>ID Collecte :</strong> {{ $pesage->id_collecte }}</p>
        <p><strong>Poids :</strong> {{ $pesage->poids }}</p>
        <p><strong>Unité :</strong> {{ $pesage->unite }}</p>
        <p><strong>Statut :</strong> {{ $pesage->statut }}</p>
        <p><strong>Description :</strong> {{ $pesage->description }}</p>
        <p><strong>Date :</strong> {{ $pesage->created_at->format('d/m/Y') }}</p>
    </div>

    <a href="{{ route('pesages.index') }}" class="btn btn-secondary mt-3">
        Retour
    </a>

</div>
@endsection
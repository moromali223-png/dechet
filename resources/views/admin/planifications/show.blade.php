@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails de la Planification</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $planification->code_planification }}</h5>
            <p><strong>Nom Tournée:</strong> {{ $planification->nom_tournee }}</p>
            <p><strong>Jour Semaine:</strong> {{ $planification->jour_semaine }}</p>
            <p><strong>Date Prévue:</strong> {{ $planification->date_prevue }}</p>
            <p><strong>Période:</strong> {{ $planification->periode }}</p>
            <p><strong>Type Collecte:</strong> {{ $planification->type_collecte }}</p>
            <p><strong>Statut:</strong> {{ $planification->statut }}</p>
            <p><strong>Zone:</strong> {{ $planification->zone->nom ?? $planification->zone->nom_zone ?? 'N/A' }}</p>
            <p><strong>Collecteur:</strong> {{ $planification->collecteur->user->name ?? $planification->collecteur->nom_collecteur ?? 'N/A' }}</p>
            <p><strong>Déclaration:</strong> {{ $planification->declaration->id ?? 'N/A' }}</p>
        </div>
    <div class="d-flex gap-2 mb-4 mt-3">  <!-- Ajoute ici les classes de marge -->
    <a href="{{ route('planifications.index') }}" class="btn btn-secondary">Retour</a>
    <a href="{{ route('planifications.edit', $planification) }}" class="btn btn-warning">Éditer</a>
</div>
@endsection
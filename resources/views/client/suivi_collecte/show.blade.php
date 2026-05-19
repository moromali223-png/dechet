@extends('layouts.app')

@section('content')

<h4>Détail collecte</h4>

<div class="card">
    <div class="card-body">

        <p><strong>Zone :</strong> {{ $planification->zone->nom }}</p>
        <p><strong>Date :</strong> {{ $planification->date_prevue }}</p>
        <p><strong>Collecteur :</strong> {{ $planification->collecteur->user->name ?? '-' }}</p>
        <p><strong>Statut :</strong> {{ $planification->statut }}</p>

        <hr>

        <h5>Suivi</h5>
        <ul>
            <li>Planifiée ✔</li>

            @if($planification->statut != 'planifiee')
                <li>Assignée ✔</li>
            @endif

            @if(in_array($planification->statut, ['en_route','en_cours','terminee']))
                <li>En cours ✔</li>
            @endif

            @if($planification->statut == 'terminee')
                <li>Collectée ✔</li>
            @endif
        </ul>

        @if($planification->collecte)
            <div class="alert alert-success">
                Collecte confirmée et réalisée ✔
            </div>
        @endif

    </div>
</div>

@endsection
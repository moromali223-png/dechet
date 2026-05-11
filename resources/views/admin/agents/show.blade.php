@extends('layouts.app')

@section('title', 'Détail Agent')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Détail de l'agent</h1>

        <a href="{{ route('agents.index') }}" class="btn btn-secondary">
            ⬅ Retour
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>Nom :</strong>
                    <p>{{ $agent->user->name }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Email :</strong>
                    <p>{{ $agent->user->email }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Téléphone :</strong>
                    <p>{{ $agent->user->telephone }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Adresse :</strong>
                    <p>{{ $agent->user->address }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Matricule :</strong>
                    <p>{{ $agent->matricul }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Qualification :</strong>
                    <p>{{ $agent->qualification }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Créé le :</strong>
                    <p>{{ $agent->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Modifié le :</strong>
                    <p>{{ $agent->updated_at->format('d/m/Y H:i') }}</p>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
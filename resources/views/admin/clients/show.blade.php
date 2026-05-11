@extends('layouts.app')

@section('title', 'Détail du Client')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Détail du client</h1>

        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
            ⬅ Retour
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>Nom :</strong>
                    <p>{{ $client->user->name }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Email :</strong>
                    <p>{{ $client->user->email }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Téléphone :</strong>
                    <p>{{ $client->user->telephone }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Zone :</strong>
                    <p>{{ $client->zone->nom ?? 'Non défini' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Type client :</strong>
                    <p>{{ $client->typeclient }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Longitude :</strong>
                    <p>{{ $client->longitude }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Latitude :</strong>
                    <p>{{ $client->latitude }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Créé le :</strong>
                    <p>{{ $client->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Modifié le :</strong>
                    <p>{{ $client->updated_at->format('d/m/Y H:i') }}</p>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
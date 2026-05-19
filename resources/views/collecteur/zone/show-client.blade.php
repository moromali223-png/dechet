@extends('collecteur.layouts.app')

@section('title', 'Détail client')

@section('content')
<div class="container py-4">

    <div class="card shadow border-0">

        <div class="card-header bg-white d-flex justify-content-between">
            <h4 class="mb-0">
                Détail client
            </h4>

            <a href="{{ route('collecteur.zone') }}" class="btn btn-secondary btn-sm">
                Retour
            </a>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="fw-bold">Nom</label>
                    <p>{{ $client->user?->name }}</p>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold">Email</label>
                    <p>{{ $client->user?->email }}</p>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold">Téléphone</label>
                    <p>{{ $client->telephone }}</p>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold">Adresse</label>
                    <p>{{ $client->adresse }}</p>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold">Zone</label>
                    <p>{{ $client->zone?->nom }}</p>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold">Date inscription</label>
                    <p>{{ $client->created_at?->format('d/m/Y') }}</p>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection
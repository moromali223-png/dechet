@extends('layouts.app')

@section('title', 'Détail Collecteur')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Détail du collecteur</h1>

        <a href="{{ route('collecteurs.index') }}" class="btn btn-secondary">
            ⬅ Retour
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>Nom :</strong>
                    <p>{{ $collecteur->name ?? 'Non défini' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Email :</strong>
                    <p>{{ $collecteur->email ?? 'Non défini' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Téléphone :</strong>
                    <p>{{ $collecteur->telephone ?? 'Non défini' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Adresse :</strong>
                    <p>{{ $collecteur->address ?? 'Non défini' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Zone :</strong>
                    <p>{{ $collecteur->zone->nom ?? 'Non défini' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Statut :</strong>
                    <p>{{ $collecteur->statut ?? 'Non défini' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Rôle :</strong>
                    <p>{{ $collecteur->role ?? 'Non défini' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Créé le :</strong>
                    <p>{{ $collecteur->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Modifié le :</strong>
                    <p>{{ $collecteur->updated_at->format('d/m/Y H:i') }}</p>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
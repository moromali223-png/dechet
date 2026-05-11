@extends('collecteur.layouts.app')

@section('title', 'Détails de la collecte')

@section('content')
<div class="container py-4">

    @php
        $plan = $collecte->planification;

        $clientName =
            $plan?->declaration?->user?->name
            ?? $plan?->abonnement?->client?->user?->name
            ?? 'Client inconnu';

        $duree = ($collecte->heure_depart && $collecte->heure_fin)
            ? \Carbon\Carbon::parse($collecte->heure_depart)->diffInMinutes($collecte->heure_fin) . ' min'
            : 'N/A';
    @endphp

    <!-- HEADER -->
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1">Collecte #{{ $collecte->id }}</h3>
                <small class="text-muted">
                    {{ $collecte->created_at?->format('d/m/Y H:i') }}
                </small>
            </div>

            <span class="badge bg-{{ $plan?->statut === 'terminee' ? 'success' : 'secondary' }} p-3">
                {{ ucfirst(str_replace('_', ' ', $plan?->statut ?? 'N/A')) }}
            </span>
        </div>
    </div>

    <div class="row">

        <!-- INFOS COLLECTE -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    📦 Informations collecte
                </div>
                <div class="card-body">

                    <p><strong>ID :</strong> {{ $collecte->id }}</p>

                    <p><strong>Commentaire :</strong><br>
                        {{ $collecte->commentaire ?? 'Aucun commentaire' }}
                    </p>

                    <p><strong>Heure départ :</strong>
                        {{ $collecte->heure_depart ?? 'N/A' }}
                    </p>

                    <p><strong>Heure fin :</strong>
                        {{ $collecte->heure_fin ?? 'N/A' }}
                    </p>

                    <p><strong>Durée :</strong> {{ $duree }}</p>

                    @if($collecte->photo)
                        <div class="mt-3">
                            <strong>Photo :</strong><br>
                            <img src="{{ asset('storage/' . $collecte->photo) }}" 
                                 class="img-fluid rounded shadow" 
                                 style="max-height:200px;">
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- INFOS PLANIFICATION -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white">
                    🗺️ Planification
                </div>
                <div class="card-body">

                    <p><strong>Code :</strong> {{ $plan?->code_planification ?? 'N/A' }}</p>

                    <p><strong>Tournée :</strong> {{ $plan?->nom_tournee ?? 'N/A' }}</p>

                    <p><strong>Date prévue :</strong> {{ $plan?->date_prevue ?? 'N/A' }}</p>

                    <p><strong>Type :</strong> {{ $plan?->type_collecte ?? 'N/A' }}</p>

                    <p><strong>Zone :</strong> {{ $plan?->zone?->nom ?? 'N/A' }}</p>

                    <p><strong>Priorité :</strong> {{ $plan?->priorite ?? 'N/A' }}</p>

                    <p><strong>Ordre passage :</strong> {{ $plan?->ordre_passage ?? 'N/A' }}</p>

                </div>
            </div>
        </div>

        <!-- CLIENT -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white">
                    👤 Client
                </div>
                <div class="card-body">
                    <p><strong>Nom :</strong> {{ $clientName }}</p>
                </div>
            </div>
        </div>

    </div>

    <a href="{{ route('collecteur.historique') }}" class="btn btn-secondary">
        ← Retour
    </a>

</div>
@endsection
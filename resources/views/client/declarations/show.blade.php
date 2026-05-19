@extends('layouts.app')

@section('title', 'Détail de la déclaration')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Détail de la déclaration #{{ $declaration->id }}</h1>
            <p class="text-muted">Toutes les informations liées à cette déclaration.</p>
        </div>
        <a href="{{ route('declarations.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Client</div>
                <div class="col-md-8">{{ $declaration->user?->name ?? 'Non spécifié' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Adresse de collecte</div>
                <div class="col-md-8">{{ $declaration->user?->address ?? 'Non renseignée' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Type de déchet</div>
                <div class="col-md-8">{{ $declaration->type_dechet }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Poids estimé</div>
                <div class="col-md-8">{{ $declaration->poids_estime ? number_format($declaration->poids_estime, 2, ',', ' ') . ' kg' : 'Non renseigné' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Statut</div>
                <div class="col-md-8">{{ ucfirst(str_replace('_', ' ', $declaration->statut)) }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Créée le</div>
                <div class="col-md-8">{{ $declaration->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Détails de l'abonnement</div>
                <div class="col-md-8">
                    @if($declaration->abonnement)
                        <p class="mb-1"><strong>Type :</strong> {{ $declaration->abonnement->type_abonnement }} (ID: #{{ $declaration->abonnement->id }})</p>
                        <p class="mb-1"><strong>Nom du client :</strong> {{ $declaration->abonnement->user?->name ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Adresse :</strong> {{ $declaration->abonnement->user?->address ?? 'N/A' }}</p>
                    @else
                        <span class="badge bg-secondary">Déclaration manuelle (Hors abonnement)</span>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Description</div>
                <div class="col-md-8">{{ $declaration->description ?? 'Aucune description' }}</div>
            </div>
            @if($declaration->photo)
                <div class="row mb-3">
                    <div class="col-md-4 fw-semibold">Photo</div>
                    <div class="col-md-8">
                        <a href="{{ asset('storage/' . $declaration->photo) }}" target="_blank">Voir la photo</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($declaration->statut === 'en_attente')
        <a href="{{ route('declarations.edit', $declaration) }}" class="btn btn-warning me-2">Modifier</a>
        @if(auth()->user()?->role === 'admin')
            <form action="{{ route('declarations.valider', $declaration) }}" method="POST" class="d-inline me-2">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Valider cette déclaration et générer la planification ?');">Valider</button>
            </form>
        @endif
        <form action="{{ route('declarations.destroy', $declaration) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?');">Supprimer</button>
        </form>
    @endif
</div>
@endsection

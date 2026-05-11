@extends('layouts.app')

@section('title', 'Gestion des déclarations')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Gestion des déclarations clients</h1>
            <p class="text-muted mb-0">Visualisez et gérez toutes les déclarations de déchets des clients.</p>
        </div>
    </div>

    @foreach($declarations as $declaration)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Déclaration #{{ $declaration->id }}</h5>
                <span class="badge {{ $declaration->statut === 'en_attente' ? 'bg-warning text-dark' : ($declaration->statut === 'planifiee' ? 'bg-info text-dark' : ($declaration->statut === 'validée' ? 'bg-success' : 'bg-secondary')) }}">
                    {{ ucfirst(str_replace('_', ' ', $declaration->statut)) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Client :</dt>
                            <dd class="col-sm-8">{{ $declaration->user?->name ?? 'N/A' }}</dd>

                            <dt class="col-sm-4">Adresse :</dt>
                            <dd class="col-sm-8">{{ $declaration->user?->address ?? 'N/A' }}</dd>

                            <dt class="col-sm-4">Abonnement :</dt>
                            <dd class="col-sm-8">{{ $declaration->abonnement?->type_abonnement ?? 'Manuel' }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Type de déchet :</dt>
                            <dd class="col-sm-8">{{ $declaration->type_dechet }}</dd>

                            <dt class="col-sm-4">Poids estimé :</dt>
                            <dd class="col-sm-8">{{ $declaration->poids_estime ? number_format($declaration->poids_estime, 2, ',', ' ') . ' kg' : 'N/A' }}</dd>

                            <dt class="col-sm-4">Créée le :</dt>
                            <dd class="col-sm-8">{{ $declaration->created_at->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>
                @if($declaration->description)
                    <hr class="my-3">
                    <p class="mb-1"><strong>Description :</strong> {{ $declaration->description }}</p>
                @endif
                @if($declaration->photo)
                    <p class="mb-0"><strong>Photo :</strong> <a href="{{ asset('storage/' . $declaration->photo) }}" target="_blank">Voir la photo</a></p>
                @endif
            </div>
            <div class="card-footer d-flex justify-content-end align-items-center">
                <a href="{{ route('declarations.show', $declaration) }}" class="btn btn-sm btn-outline-primary me-2">Voir détails</a>
                @if($declaration->statut === 'en_attente')
                    <form method="POST" action="{{ route('admin.declarations.valider', $declaration) }}" class="d-inline me-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirmer la validation de cette déclaration ?');">Valider</button>
                    </form>

                    <form method="POST" action="{{ route('admin.declarations.rejeter', $declaration) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer le rejet de cette déclaration ?');">Rejeter</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach

    {{ $declarations->links() }}

</div>
@endsection
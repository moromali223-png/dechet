@extends('layouts.app')

@section('title', 'Détails de l\'abonnement')

@section('content')

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Abonnement #{{ $abonnement->id }}
            </h2>
            <p class="text-muted mb-0">
                Détails complets de l’abonnement
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('abonnements.index') }}" class="btn btn-outline-secondary">
                ← Retour
            </a>

            {{-- ADMIN ONLY ACTIONS --}}
            @if(auth()->user()->role === 'admin')

                <a href="{{ route('abonnements.edit', $abonnement->id) }}"
                   class="btn btn-warning">
                    Modifier
                </a>

                @if($abonnement->statut === 'en_attente')

                    <form action="{{ route('abonnements.activer', $abonnement->id) }}"
                          method="POST">
                        @csrf
                        @method('PATCH')

                        <button class="btn btn-success"
                                onclick="return confirm('Activer cet abonnement ?')">
                            Activer
                        </button>
                    </form>

                    <a href="{{ route('abonnements.rejeter.form', $abonnement->id) }}"
                       class="btn btn-danger">
                        Rejeter
                    </a>

                @endif

            @endif

        </div>
    </div>

    <div class="row g-4">

        {{-- CLIENT INFO --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-primary text-white">
                    Client
                </div>

                <div class="card-body">

                    <p class="mb-3">
                        <span class="text-muted">Nom</span><br>
                        <strong>{{ $abonnement->user?->name }}</strong>
                    </p>

                    <p class="mb-3">
                        <span class="text-muted">Email</span><br>
                        {{ $abonnement->user?->email }}
                    </p>

                    <p class="mb-0">
                        <span class="text-muted">Zone</span><br>
                        {{ $abonnement->zone?->nom }}
                    </p>

                </div>

            </div>

        </div>

        {{-- ABONNEMENT INFO --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-success text-white">
                    Informations abonnement
                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <small class="text-muted">Type abonnement</small>
                            <div class="fw-semibold">{{ $abonnement->type_abonnement }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Type déchet</small>
                            <div class="fw-semibold">{{ $abonnement->type_dechet }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Fréquence</small>
                            <div>{{ ucfirst($abonnement->frequence) }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Jour collecte</small>
                            <div>{{ ucfirst($abonnement->jour_collecte) }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Poids estimé</small>
                            <div>{{ number_format($abonnement->poids_estime, 2, ',', ' ') }} kg</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Montant</small>
                            <div class="fw-bold text-success">
                                {{ number_format($abonnement->montant, 0, ',', ' ') }} FCFA
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Début</small>
                            <div>{{ $abonnement->date_debut->format('d/m/Y') }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Fin</small>
                            <div>{{ $abonnement->date_fin->format('d/m/Y') }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Statut</small>
                            <div>
                                @php
                                    $statusClass = match($abonnement->statut) {
                                        'actif' => 'success',
                                        'en_attente' => 'warning',
                                        'annule', 'annulé' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp

                                <span class="badge bg-{{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $abonnement->statut)) }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Créé le</small>
                            <div>{{ $abonnement->created_at->format('d/m/Y H:i') }}</div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- ADRESSE --}}
        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-info text-white">
                    Adresse de collecte
                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <small class="text-muted">Rue</small>
                            <div>{{ $abonnement->rue ?? '-' }}</div>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted">Quartier</small>
                            <div>{{ $abonnement->quartier ?? '-' }}</div>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted">Porte</small>
                            <div>{{ $abonnement->porte ?? '-' }}</div>
                        </div>

                        <div class="col-12">
                            <small class="text-muted">Adresse complète</small>
                            <div class="fw-semibold">
                                {{ $abonnement->adresse_complete }}
                            </div>
                        </div>

                        @if($abonnement->repere)
                            <div class="col-12">
                                <small class="text-muted">Repère</small>
                                <div class="text-primary">
                                    {{ $abonnement->repere }}
                                </div>
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
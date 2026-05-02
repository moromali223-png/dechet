@extends('layouts.app')

@section('title', 'Modifier un abonnement')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Modifier l'abonnement</h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6 class="alert-heading mb-2">
                                <i class="bx bx-error-circle me-1"></i>
                                Veuillez corriger les erreurs suivantes :
                            </h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('abonnements.update', $abonnement->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Type d'abonnement -->
                        <div class="mb-3">
                            <label for="type_abonnement" class="form-label">
                                Type d'abonnement <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="type_abonnement"
                                name="type_abonnement"
                                class="form-control @error('type_abonnement') is-invalid @enderror"
                                value="{{ old('type_abonnement', $abonnement->type_abonnement) }}"
                                required
                            >
                            @error('type_abonnement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type de déchet -->
                        <div class="mb-3">
                            <label for="type_dechet" class="form-label">
                                Type de déchet <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="type_dechet"
                                name="type_dechet"
                                class="form-control @error('type_dechet') is-invalid @enderror"
                                value="{{ old('type_dechet', $abonnement->type_dechet) }}"
                                required
                            >
                            @error('type_dechet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fréquence et jour -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="frequence" class="form-label">
                                    Fréquence <span class="text-danger">*</span>
                                </label>
                                <select
                                    id="frequence"
                                    name="frequence"
                                    class="form-select @error('frequence') is-invalid @enderror"
                                    required
                                >
                                    <option value="hebdomadaire"
                                        {{ old('frequence', $abonnement->frequence) == 'hebdomadaire' ? 'selected' : '' }}>
                                        Hebdomadaire
                                    </option>
                                    <option value="mensuelle"
                                        {{ old('frequence', $abonnement->frequence) == 'mensuelle' ? 'selected' : '' }}>
                                        Mensuelle
                                    </option>
                                </select>
                                @error('frequence')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="jour_collecte" class="form-label">
                                    Jour de collecte <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="jour_collecte"
                                    name="jour_collecte"
                                    class="form-control @error('jour_collecte') is-invalid @enderror"
                                    value="{{ old('jour_collecte', $abonnement->jour_collecte) }}"
                                    required
                                >
                                @error('jour_collecte')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Poids et montant -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="poids_estime" class="form-label">
                                    Poids estimé (kg) <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="number"
                                    id="poids_estime"
                                    name="poids_estime"
                                    class="form-control @error('poids_estime') is-invalid @enderror"
                                    value="{{ old('poids_estime', $abonnement->poids_estime) }}"
                                    step="0.01"
                                    min="0"
                                    required
                                >
                                @error('poids_estime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="montant" class="form-label">
                                    Montant
                                </label>
                                <input
                                    type="number"
                                    id="montant"
                                    name="montant"
                                    class="form-control @error('montant') is-invalid @enderror"
                                    value="{{ old('montant', $abonnement->montant) }}"
                                    step="0.01"
                                    min="0"
                                >
                                @error('montant')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_debut" class="form-label">
                                    Date de début <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="date_debut"
                                    name="date_debut"
                                    class="form-control @error('date_debut') is-invalid @enderror"
                                    value="{{ old('date_debut', optional($abonnement->date_debut)->format('Y-m-d')) }}"
                                    required
                                >
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_fin" class="form-label">
                                    Date de fin <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="date_fin"
                                    name="date_fin"
                                    class="form-control @error('date_fin') is-invalid @enderror"
                                    value="{{ old('date_fin', optional($abonnement->date_fin)->format('Y-m-d')) }}"
                                    required
                                >
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="mb-4">
                            <label for="statut" class="form-label">
                                Statut <span class="text-danger">*</span>
                            </label>
                            <select
                                id="statut"
                                name="statut"
                                class="form-select @error('statut') is-invalid @enderror"
                                required
                            >
                                <option value="en_attente"
                                    {{ old('statut', $abonnement->statut) == 'en_attente' ? 'selected' : '' }}>
                                    En attente
                                </option>
                                <option value="actif"
                                    {{ old('statut', $abonnement->statut) == 'actif' ? 'selected' : '' }}>
                                    Actif
                                </option>
                                <option value="expire"
                                    {{ old('statut', $abonnement->statut) == 'expire' ? 'selected' : '' }}>
                                    Expiré
                                </option>
                                <option value="annule"
                                    {{ old('statut', $abonnement->statut) == 'annule' ? 'selected' : '' }}>
                                    Annulé
                                </option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('abonnements.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>
                                Retour
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
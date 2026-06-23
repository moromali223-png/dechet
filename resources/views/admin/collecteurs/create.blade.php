@extends('layouts.app')

@section('title', 'Ajouter un Collecteur')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">
        <h4 class="mb-1 fw-bold">
            Ajouter un Collecteur
        </h4>
        <small class="text-muted">
            Enregistrer un nouveau collecteur dans le système
        </small>
    </div>

    <div class="card-body">

        {{-- Affichage des erreurs de session --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Affichage des erreurs de validation --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('collecteurs.store') }}" method="POST">

            @csrf

            <div class="row g-3">

                <!-- ==================== INFORMATIONS UTILISATEUR ==================== -->
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-user me-2"></i>Informations Utilisateur
                    </h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nom complet</label>
                    <input type="text" 
                           name="nom" 
                           class="form-control @error('nom') is-invalid @enderror" 
                           value="{{ old('nom') }}" 
                           required>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" 
                           name="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input type="text" 
                           name="telephone" 
                           class="form-control @error('telephone') is-invalid @enderror" 
                           value="{{ old('telephone') }}">
                    @error('telephone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" 
                           name="mot_de_passe" 
                           class="form-control @error('mot_de_passe') is-invalid @enderror" 
                           required>
                    @error('mot_de_passe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirmation du mot de passe -->
                <div class="col-md-6">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" 
                           name="password_confirmation" 
                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                           required>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Adresse</label>
                    <input type="text" 
                           name="address" 
                           class="form-control @error('address') is-invalid @enderror" 
                           value="{{ old('address') }}">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <!-- ==================== INFORMATIONS COLLECTEUR ==================== -->
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-user-check me-2"></i>Informations Collecteur
                    </h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Numéro de permis</label>
                    <input type="text" 
                           name="numpermis" 
                           class="form-control @error('numpermis') is-invalid @enderror" 
                           value="{{ old('numpermis') }}" 
                           required>
                    @error('numpermis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Zone</label>
                    <select name="zone_id" 
                            class="form-select @error('zone_id') is-invalid @enderror">
                        <option value="">-- Sélectionner une zone --</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" 
                                    {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                {{ $zone->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('zone_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tu peux ajouter ici d'autres champs comme Matricule si nécessaire -->

            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('collecteurs.index') }}" class="btn btn-light">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Enregistrer le collecteur
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
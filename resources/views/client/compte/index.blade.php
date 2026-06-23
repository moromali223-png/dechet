@extends('layouts.app')

@section('title', 'Mon compte')

@section('content')
<div class="container py-4" style="max-width: 760px;">

    {{-- Message de succès --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    {{-- Erreurs --}}
    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-lg border-0 overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-primary text-white p-4 p-md-5 text-center">
            <div class="mb-4">
                <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center shadow-sm mx-auto"
                     style="width: 120px; height: 120px;">
                    <i class="bi bi-person-fill text-success" style="font-size: 4.2rem;"></i>
                </div>
            </div>

            <h2 class="fw-bold mb-1">{{ $user->name }}</h2>
            <p class="opacity-75 mb-0">{{ $user->email }}</p>
        </div>

        {{-- BODY --}}
        <div class="card-body p-4 p-md-5">

            <form action="{{ route('client.compte.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Informations personnelles --}}
                <div class="mb-5">
                    <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">
                        <i class="bi bi-person-vcard me-2"></i>
                        Informations personnelles
                    </h5>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}"
                                       required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Adresse email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}"
                                       required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Sécurité --}}
                <div class="mb-5">
                    <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">
                        <i class="bi bi-shield-lock me-2"></i>
                        Sécurité du compte
                    </h5>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nouveau mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Laisser vide si inchangé">
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirmation du mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control"
                                       placeholder="Confirmer le nouveau mot de passe">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-5">

               {{-- Actions --}}
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-3">

    <small class="text-muted">
        Dernière mise à jour :
        <strong>{{ $user->updated_at?->format('d/m/Y à H:i') }}</strong>
    </small>

    <div class="d-flex gap-2 w-100 w-sm-auto justify-content-end">

        {{-- ANNULER (secondaire discret) --}}
        <a href="{{ route('dashboard') }}"
           class="btn btn-light border px-4 py-2 shadow-sm d-flex align-items-center gap-2">

            <i class="bi bi-x-circle text-muted"></i>
            <span>Annuler</span>
        </a>

        {{-- ENREGISTRER (PRIMARY ACTION) --}}
        <button type="submit"
                class="btn btn-primary px-5 py-2 shadow-sm d-flex align-items-center gap-2 fw-semibold">

            <i class="bi bi-check-circle-fill"></i>
            <span>Enregistrer</span>
        </button>

    </div>

</div>

            </form>

        </div>
    </div>
</div>
@endsection
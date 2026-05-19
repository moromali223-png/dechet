@extends('layouts.app')

@section('title', 'Mon compte')

@section('content')
<div class="container py-4" style="max-width: 760px;">

    {{-- message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- erreurs --}}
    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="card shadow-lg border-0 overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-success text-white p-4 text-center">

            <div class="mb-3">
                <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center shadow"
                     style="width:110px;height:110px;">
                    <i class="bi bi-person-fill text-success" style="font-size: 4rem;"></i>
                </div>
            </div>

            <h3 class="fw-bold mb-1">{{ $user->name }}</h3>
            <small class="opacity-75">{{ $user->email }}</small>
        </div>


        {{-- BODY --}}
        <div class="card-body p-4 p-md-5">

            <form action="{{ route('client.compte.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- INFORMATIONS --}}
                <div class="mb-5">

                    <h5 class="fw-bold mb-4 text-success">
                        <i class="bi bi-person-vcard me-2"></i>
                        Informations personnelles
                    </h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom complet</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}">
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Adresse email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>


                {{-- MOT DE PASSE --}}
                <div class="mb-4">

                    <h5 class="fw-bold mb-4 text-success">
                        <i class="bi bi-shield-lock me-2"></i>
                        Sécurité du compte
                    </h5>

                    <div class="row g-3">

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
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirmation</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control"
                                       placeholder="Confirmer">
                            </div>
                        </div>

                    </div>
                </div>


                <hr class="my-4">


                {{-- ACTION --}}
                <div class="d-flex justify-content-between align-items-center">

                    <small class="text-muted">
                        Dernière mise à jour : {{ $user->updated_at?->format('d/m/Y H:i') }}
                    </small>

                    <button class="btn btn-success px-4 py-2 shadow-sm">
                        <i class="bi bi-check-circle me-2"></i>
                        Enregistrer
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
@endsection
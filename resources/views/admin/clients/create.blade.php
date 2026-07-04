@extends('layouts.app')

@section('title', 'Ajouter un Client')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">
        <h4 class="mb-1 fw-bold">
            Ajouter un Client
        </h4>
        <small class="text-muted">
            Enregistrer un nouveau client dans le système
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

       <form action="{{ route('clients.store') }}" method="POST">
    @csrf

    <div class="row g-3">

        <div class="col-md-6">
            <label>Nom complet</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="col-md-6">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="col-md-6">
            <label>Téléphone</label>
            <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}">
        </div>
         <div class="col-md-6">
            <label>Zone</label>
            <select name="zone_id" class="form-control">
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}">{{ $zone->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label>Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label>Confirmer mot de passe</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="col-12">
            <label>Adresse</label>
            <input type="text" name="address" class="form-control">
        </div>

       

    </div>
    <a href="{{ route('clients.index') }}" class="btn btn-secondary mt-3">
            Retour
    </a>

    <button class="btn btn-primary mt-3">
        Enregistrer
    </button>
</form>
    </div>
</div>

@endsection
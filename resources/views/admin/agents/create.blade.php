@extends('layouts.app')

@section('title', 'Ajouter Agent')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">
        <h4 class="mb-1 fw-bold">
            Ajouter un Agent
        </h4>
        <small class="text-muted">
            Enregistrer un nouvel agent dans le système
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

       <form action="{{ route('agents.store') }}" method="POST">
@csrf

<div class="row g-3">

    <div class="col-md-6">
        <label>Nom complet</label>
        <input type="text" name="nom" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label>Téléphone</label>
        <input type="text" name="telephone" class="form-control">
    </div>

    <div class="col-md-6">
        <label>Adresse</label>
        <input type="text" name="address" class="form-control">
    </div>

    <div class="col-md-6">
        <label>Mot de passe</label>
        <input type="password" name="mot_de_passe" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label>Confirmation</label>
        <input type="password" name="mot_de_passe_confirmation" class="form-control" required>
    </div>

</div>
<a href="{{ route('agents.index') }}" class="btn btn-secondary  mt-3">
            Retour
        </a>

<button class="btn btn-primary mt-3">
    Enregistrer
</button>

</form>
    </div>
</div>

@endsection
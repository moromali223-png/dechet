@extends('layouts.app')

@section('title', 'Ajouter un Collecteur')

@section('content')
<div class="container">
    <h1>Ajouter un Collecteur</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('collecteurs.store') }}" method="POST">
        @csrf

        <h4>Informations utilisateur</h4>
        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label>Téléphone</label>
            <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}">
        </div>
        <div class="mb-3">
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Adresse</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
        </div>

        <h4>Informations Collecteur</h4>
        <div class="mb-3">
            <label>Numéro permis</label>
            <input type="text" name="numpermis" class="form-control" value="{{ old('numpermis') }}" required>
        </div>
        
        <div class="mb-3">
            <label>Zone</label>
            <select name="zone_id" class="form-control">
                <option value="">-- Sélectionner une zone --</option>
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                        {{ $zone->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
@endsection
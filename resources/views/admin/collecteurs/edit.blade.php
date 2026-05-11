@extends('layouts.app')

@section('title', 'Modifier le Collecteur')

@section('content')
<div class="container">
    <h1>Modifier le Collecteur</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('collecteurs.update', $collecteur->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h4>Informations utilisateur</h4>
        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom', $collecteur->user->name) }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $collecteur->user->email) }}" required>
        </div>
        <div class="mb-3">
            <label>Téléphone</label>
            <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $collecteur->user->telephone) }}">
        </div>
        <div class="mb-3">
            <label>Mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" name="mot_de_passe" class="form-control">
        </div>
        <div class="mb-3">
            <label>Adresse</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $collecteur->user->address) }}">
        </div>

        <h4>Informations Collecteur</h4>
        <div class="mb-3">
            <label>Numéro permis</label>
            <input type="text" name="numpermis" class="form-control" value="{{ old('numpermis', $collecteur->numpermis) }}" required>
        </div>
       
        <div class="mb-3">
            <label>Zone</label>
            <select name="zone_id" class="form-control">
                <option value="">-- Sélectionner une zone --</option>
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}" {{ old('zone_id', $collecteur->zone_id) == $zone->id ? 'selected' : '' }}>
                        {{ $zone->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Mettre à jour</button>
    </form>
</div>
@endsection
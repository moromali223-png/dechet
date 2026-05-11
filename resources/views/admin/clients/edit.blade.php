@extends('layouts.app')

@section('title', 'Modifier un Client')

@section('content')

<div class="container">
    <h1>Modifier un Client</h1>

    {{-- Affichage des erreurs --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('clients.update', $client->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h4>Informations utilisateur</h4>

        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control"
                   value="{{ old('nom', $client->user->name) }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $client->user->email) }}" required>
        </div>

        <div class="mb-3">
            <label>Téléphone</label>
            <input type="text" name="telephone" class="form-control"
                   value="{{ old('telephone', $client->user->telephone) }}">
        </div>

        <div class="mb-3">
            <label>Mot de passe (laisser vide pour ne pas modifier)</label>
            <input type="password" name="mot_de_passe" class="form-control">
        </div>

        <div class="mb-3">
            <label>Adresse</label>
            <input type="text" name="address" class="form-control"
                   value="{{ old('address', $client->user->address) }}">
        </div>

        <hr>

        <h4>Informations Client</h4>

        <div class="mb-3">
            <label>Type de client</label>
            <select name="typeclient" class="form-control">
                <option value="particulier"
                    {{ $client->typeclient == 'particulier' ? 'selected' : '' }}>
                    Particulier
                </option>
                <option value="entreprise"
                    {{ $client->typeclient == 'entreprise' ? 'selected' : '' }}>
                    Entreprise
                </option>
                <option value="marche"
                    {{ $client->typeclient == 'marche' ? 'selected' : '' }}>
                    Marche
                </option>
                <option value="autres"
                    {{ $client->typeclient == 'autres' ? 'selected' : '' }}>
                    Autres
                </option>
                
            </select>
        </div>

        <div class="mb-3">
            <label>Latitude</label>
            <input type="text" name="latitude" class="form-control"
                   value="{{ old('latitude', $client->latitude) }}">
        </div>

        <div class="mb-3">
            <label>Longitude</label>
            <input type="text" name="longitude" class="form-control"
                   value="{{ old('longitude', $client->longitude) }}">
        </div>

        <div class="mb-3">
            <label>Zone</label>
            <select name="zone_id" class="form-control">
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}"
                        {{ $client->zone_id == $zone->id ? 'selected' : '' }}>
                        {{ $zone->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-success">
            Modifier
        </button>

        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
            Retour
        </a>

    </form>
</div>

@endsection
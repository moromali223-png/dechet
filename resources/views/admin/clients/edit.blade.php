@extends('layouts.app')

@section('title', 'Modifier Client')

@section('content')

<div class="container">

    <h1 class="mb-4">Modifier un client</h1>

    <form action="{{ route('clients.update', $client->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Nom</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $client->name) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', $client->email) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Téléphone</label>
                <input type="text" name="telephone" class="form-control"
                       value="{{ old('telephone', $client->telephone) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Mot de passe (optionnel)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="col-md-12 mb-3">
                <label>Adresse</label>
                <input type="text" name="address" class="form-control"
                       value="{{ old('address', $client->address) }}">
            </div>

            <div class="col-md-6 mb-3">
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

        </div>

        <button class="btn btn-primary">
            Modifier
        </button>

        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
            Retour
        </a>

    </form>

</div>

@endsection
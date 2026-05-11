@extends('layouts.app')

@section('title', 'Modifier l\'Agent')

@section('content')
<div class="container">
    <h1>Modifier l'Agent</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('agents.update', $agent->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h4>Informations utilisateur</h4>
        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom', $agent->user->name) }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $agent->user->email) }}" required>
        </div>
        <div class="mb-3">
            <label>Téléphone</label>
            <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $agent->user->telephone) }}">
        </div>
        <div class="mb-3">
            <label>Mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" name="mot_de_passe" class="form-control">
        </div>
        <div class="mb-3">
            <label>Adresse</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $agent->user->address) }}">
        </div>

        <h4>Informations Agent</h4>
        <div class="mb-3">
            <label>Matricule</label>
            <input type="text" name="matricul" class="form-control" value="{{ old('matricul', $agent->matricul) }}" required>
        </div>
        <div class="mb-3">
            <label>Qualification</label>
            <input type="text" name="qualification" class="form-control" value="{{ old('qualification', $agent->qualification) }}" required>
        </div>

        <button type="submit" class="btn btn-success">Mettre à jour</button>
    </form>
</div>
@endsection
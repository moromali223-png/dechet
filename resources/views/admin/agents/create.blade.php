@extends('layouts.app')

@section('title', 'Ajouter Agent')

@section('content')
<div class="container">
    <h1>Ajouter un Agent</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('agents.store') }}" method="POST">
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

        <h4>Informations Agent</h4>
    
         <div class="mb-3">
            <label>Qualification</label>
            <select name="qualification" class="form-control">
                <option value="">-- Choisir --</option>
                <option value="agent_tri">Agent de trie</option>
                <option value="agent_prod">Agent de Productions</option>
                <option value="superviseur">Superviseur</option>
                <option value="manager">Manager</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>

@endsection
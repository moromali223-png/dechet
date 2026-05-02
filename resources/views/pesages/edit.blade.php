@extends('layouts.app')

@section('title', 'Modifier le pesage')

@section('content')
<div class="container">

    <h1>Modifier le pesage</h1>

    <form action="{{ route('pesages.update', $pesage->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Collecte --}}
        <div class="mb-3">
            <label class="form-label">Collecte</label>
            <select name="id_collecte" class="form-control">
                @foreach($collectes as $collecte)
                    <option value="{{ $collecte->id }}"
                        {{ $collecte->id == $pesage->id_collecte ? 'selected' : '' }}>
                        Collecte #{{ $collecte->id }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Poids --}}
        <div class="mb-3">
            <label class="form-label">Poids</label>
            <input type="number" name="poids" class="form-control"
                   value="{{ $pesage->poids }}" required>
        </div>

        {{-- Unité --}}
        <div class="mb-3">
            <label class="form-label">Unité</label>
            <input type="text" name="unite" class="form-control"
                   value="{{ $pesage->unite }}" required>
        </div>

        {{-- Statut --}}
        <div class="mb-3">
            <label class="form-label">Statut</label>
            <select name="statut" class="form-control">
                <option value="EN_ATTENTE" {{ $pesage->statut == 'EN_ATTENTE' ? 'selected' : '' }}>En attente</option>
                <option value="VALIDÉ" {{ $pesage->statut == 'VALIDÉ' ? 'selected' : '' }}>Validé</option>
                <option value="REFUSÉ" {{ $pesage->statut == 'REFUSÉ' ? 'selected' : '' }}>Refusé</option>
            </select>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ $pesage->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('pesages.index') }}" class="btn btn-secondary">Annuler</a>

    </form>

</div>
@endsection
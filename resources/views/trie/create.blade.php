@extends('layouts.app')

@section('title', 'Nouveau tri')

@section('content')
<div class="container">

    <h1>Ajouter un tri</h1>

    {{-- erreurs --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tries.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Type de déchet</label>
            <input type="text" name="type_dechet"
                class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Quantité</label>
            <input type="number" step="0.01" name="quantite_trier"
                class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Unité</label>
            <input type="text" name="unite"
                class="form-control" value="kg">
        </div>

        <div class="mb-3">
            <label>Pesage</label>
            <select name="pesage_id" class="form-control" required>
                @foreach($pesages as $pesage)
                    <option value="{{ $pesage->id }}">
                        Pesage #{{ $pesage->id }} ({{ $pesage->poids }} kg)
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('tries.index') }}" class="btn btn-secondary">Annuler</a>

    </form>

</div>
@endsection
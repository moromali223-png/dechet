@extends('layouts.app')

@section('title', 'Modifier le tri')

@section('content')
<div class="container">

    <h1>Modifier le tri</h1>

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

    <form action="{{ route('tries.update', $tri) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Type de déchet</label>
            <input type="text" name="type_dechet"
                value="{{ $tri->type_dechet }}"
                class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Quantité</label>
            <input type="number" step="0.01" name="quantite_trier"
                value="{{ $tri->quantite_trier }}"
                class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Unité</label>
            <input type="text" name="unite"
                value="{{ $tri->unite }}"
                class="form-control">
        </div>

        <div class="mb-3">
            <label>Pesage</label>
            <select name="pesage_id" class="form-control">
                @foreach($pesages as $pesage)
                    <option value="{{ $pesage->id }}"
                        {{ $tri->pesage_id == $pesage->id ? 'selected' : '' }}>
                        Pesage #{{ $pesage->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('tries.index') }}" class="btn btn-secondary">Annuler</a>

    </form>

</div>
@endsection
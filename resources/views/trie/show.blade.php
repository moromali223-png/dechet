@extends('layouts.app')

@section('title', 'Détail du tri')

@section('content')
<div class="container">

    <h1>Détail du tri</h1>

    <div class="card shadow p-4">

        <p><strong>ID :</strong> {{ $tri->id }}</p>

        <p><strong>Type de déchet :</strong>
            <span class="badge bg-info">
                {{ $tri->type_dechet }}
            </span>
        </p>

        <p><strong>Quantité :</strong>
            {{ $tri->quantite_trier }} {{ $tri->unite }}
        </p>

        <p><strong>Pesage associé :</strong>
            #{{ $tri->pesage->id ?? 'N/A' }}
        </p>

        <p><strong>Date :</strong>
          {{ optional($tri->created_at)->format('d/m/Y H:i') ?? 'Non défini' }}
        </p>


    </div>

    <div class="mt-3">
        <a href="{{ route('tries.index') }}" class="btn btn-secondary">Retour</a>
       <a href="{{ route('tries.edit', $tri) }}" class="btn btn-warning">
    Modifier
</a>
    </div>

</div>
@endsection
@extends('layouts.app')

@section('title', 'Modifier Paiement')

@section('content')
<div class="container">
    <h1 class="mb-4">Modifier le paiement</h1>

    {{-- Affichage des erreurs --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('paiements.update', $paiement->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Mode paiement --}}
        <div class="mb-3">
            <label class="form-label">Mode de paiement</label>
            <input type="text" name="mode_paiement" class="form-control"
                   value="{{ old('mode_paiement', $paiement->mode_paiement) }}" required>
        </div>

        {{-- Montant --}}
        <div class="mb-3">
            <label class="form-label">Montant</label>
            <input type="number" name="montant" class="form-control"
                   value="{{ old('montant', $paiement->montant) }}" required>
        </div>

        {{-- Type paiement --}}
        <div class="mb-3">
            <label class="form-label">Type paiement</label>
            <input type="text" name="type_paiement" class="form-control"
                   value="{{ old('type_paiement', $paiement->type_paiement) }}">
        </div>

        {{-- Référence --}}
        <div class="mb-3">
            <label class="form-label">Référence</label>
            <input type="text" name="reference_paiement" class="form-control"
                   value="{{ old('reference_paiement', $paiement->reference_paiement) }}">
        </div>

        {{-- Statut --}}
        <div class="mb-3">
            <label class="form-label">Statut</label>
            <select name="statut" class="form-control" required>
                <option value="en_attente" {{ $paiement->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="valide" {{ $paiement->statut == 'valide' ? 'selected' : '' }}>Valide</option>
                <option value="echoue" {{ $paiement->statut == 'echoue' ? 'selected' : '' }}>Échoué</option>
            </select>
        </div>

        {{-- Commande --}}
        <div class="mb-3">
            <label class="form-label">Commande (optionnel)</label>
            <select name="commande_id" class="form-control">
                <option value="">-- Aucune --</option>
                @foreach($commandes as $commande)
                    <option value="{{ $commande->id }}"
                        {{ $paiement->commande_id == $commande->id ? 'selected' : '' }}>
                        Commande #{{ $commande->id }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Abonnement --}}
        <div class="mb-3">
            <label class="form-label">Abonnement ID (optionnel)</label>
            <input type="number" name="abonnement_id" class="form-control"
                   value="{{ old('abonnement_id', $paiement->abonnement_id) }}">
        </div>

        {{-- Dates --}}
        <div class="mb-3">
            <label class="form-label">Créé le</label>
            <input type="text" class="form-control"
                   value="{{ $paiement->created_at->format('d/m/Y H:i') }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Modifié le</label>
            <input type="text" class="form-control"
                   value="{{ $paiement->updated_at->format('d/m/Y H:i') }}" disabled>
        </div>

        {{-- Boutons --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('paiements.index') }}" class="btn btn-secondary">
                ⬅ Retour
            </a>

            <button type="submit" class="btn btn-success">
               Modifier
            </button>
        </div>

    </form>
</div>
@endsection
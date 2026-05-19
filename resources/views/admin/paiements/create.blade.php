@extends('layouts.app')

@section('title', 'Créer un paiement')

@section('content')
<div class="container">
    <h1>Créer un paiement</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('paiements.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="commande_id" class="form-label">Lier à une Commande</label>
            <select name="commande_id" id="commande_id" class="form-select" onchange="if(this.value) document.getElementById('abonnement_id').value=''">
                <option value="">Aucune</option>
                @foreach($commandes as $commande)
                    <option value="{{ $commande->id }}" {{ old('commande_id') == $commande->id ? 'selected' : '' }}>
                        {{ $commande->code_commande }} - {{ $commande->client->user->name ?? $commande->client->name ?? 'Client' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="abonnement_id" class="form-label">OU Lier à un Abonnement</label>
            <select name="abonnement_id" id="abonnement_id" class="form-select" onchange="if(this.value) document.getElementById('commande_id').value=''">
                <option value="">-- Aucun abonnement --</option>
                @foreach($abonnements as $abonnement)
                    <option value="{{ $abonnement->id }}" {{ old('abonnement_id') == $abonnement->id ? 'selected' : '' }}>
                        ABO-#{{ $abonnement->id }} - {{ $abonnement->client->user->name ?? $abonnement->client->name ?? 'Client' }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Sélectionner l'un ou l'autre. Le champ opposé sera vidé automatiquement.</small>
        </div>

        <div class="mb-3">
            <label for="mode_paiement" class="form-label">Mode de paiement</label>
            <input type="text" name="mode_paiement" id="mode_paiement" class="form-control" value="{{ old('mode_paiement') }}" required>
        </div>

        <div class="mb-3">
            <label for="montant" class="form-label">Montant</label>
            <input type="number" step="0.01" name="montant" id="montant" class="form-control" value="{{ old('montant') }}" required>
        </div>

        <div class="mb-3">
            <label for="type_paiement" class="form-label">Type de paiement</label>
            <input type="text" name="type_paiement" id="type_paiement" class="form-control" value="{{ old('type_paiement') }}">
        </div>
       <div class="mb-3">
           <label for="statut" class="form-label">Statut du paiement</label>
          <select name="statut" id="statut" class="form-select" required>
           <option value="en_attente">En attente</option>
           <option value="valide">Valide</option>
           <option value="echoue">Échoué</option>
          </select>
       </div>
        <div class="mb-3">
            <label for="reference_paiement" class="form-label">Référence de paiement</label>
            <input type="text" name="reference_paiement" id="reference_paiement" class="form-control" value="{{ old('reference_paiement') }}">
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('paiements.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </form>
</div>
@endsection

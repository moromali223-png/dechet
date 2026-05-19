@extends('layouts.app')

@section('title', 'Détail du paiement')

@section('content')
<div class="container">
    <h1>Détail du paiement</h1>

    <p><strong>ID :</strong> {{ $paiement->id }}</p>
    <p><strong>Montant :</strong> {{ $paiement->montant }}</p>
    <p><strong>Mode :</strong> {{ $paiement->mode_paiement }}</p>
    <p><strong>Statut :</strong> {{ $paiement->statut }}</p>
    <p><strong>Identifiant lié :</strong>
    @if($paiement->commande_id === null)
        Abonnement ID : {{ $paiement->abonnement_id }}
    @elseif($paiement->abonnement_id === null)
        Paiement ID : {{ $paiement->id }}
    @else
        Commande ID : {{ $paiement->commande_id }}
    @endif
</p>
    <p><strong>Référence :</strong> {{ $paiement->reference_paiement }}</p>
<p><strong>Créé le :</strong> {{ $paiement->created_at->format('d/m/Y H:i') }}</p>
<p><strong>Modifié le :</strong> {{ $paiement->updated_at->format('d/m/Y H:i') }}</p>

    <a href="{{ route('paiements.index') }}" class="btn btn-primary">
        Retour
    </a>
</div>
@endsection
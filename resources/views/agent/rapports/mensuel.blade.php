@extends('agent.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3>Rapport mensuel</h3>

            <p>Mois sélectionné : {{ request('mois') }}</p>

            <hr>

            <ul>
                <li>Total poids : {{ $stats['poids_total'] ?? 0 }}</li>
                <li>Quantité triée : {{ $stats['quantite_triee'] ?? 0 }}</li>
                <li>Produits fabriqués : {{ $stats['produits_fabriqués'] ?? 0 }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection
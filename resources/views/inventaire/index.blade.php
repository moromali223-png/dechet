@extends('layouts.app')

@section('title', 'Inventaire - Stock Actuel')

@section('content')

<div class="card shadow-sm border-0">

    {{-- HEADER --}}
    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center">

        <div>
            <h4 class="card-title mb-1 fw-bold">
                Inventaire
            </h4>

            <small class="text-muted">
                Suivi en temps réel des stocks disponibles
            </small>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('inventaire.create') }}"
               class="btn btn-primary btn-sm">
                Ajouter Stock
            </a>

            <a href="{{ route('dashboard') }}"
               class="btn btn-outline-secondary btn-sm">
                Tableau de bord
            </a>

        </div>

    </div>

    <div class="card-body">

        {{-- ALERTES --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


        {{-- STATISTIQUES --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <p class="text-muted small mb-2">
                            Produits suivis
                        </p>

                        <h4 class="fw-bold mb-0">
                            {{ $totalProduits }}
                        </h4>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <p class="text-muted small mb-2">
                            Quantité totale
                        </p>

                        <h4 class="fw-bold mb-0">
                            {{ number_format($stockTotal, 2, ',', ' ') }}
                        </h4>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <p class="text-muted small mb-2">
                            Valeur du stock
                        </p>

                        <h4 class="fw-bold mb-0">
                            {{ number_format($valeurTotale, 0, ',', ' ') }} FCFA
                        </h4>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100
                    {{ $produitsEnAlerte > 0 ? 'border border-danger' : '' }}">

                    <div class="card-body">

                        <p class="text-muted small mb-2">
                            Produits en alerte
                        </p>

                        <h4 class="fw-bold mb-0
                            text-{{ $produitsEnAlerte > 0 ? 'danger' : 'success' }}">

                            {{ $produitsEnAlerte }}

                        </h4>

                    </div>
                </div>
            </div>

        </div>


        {{-- TABLEAU --}}
        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>Code</th>

                        <th>Produit</th>

                        <th class="text-end">
                            Quantité
                        </th>

                        <th class="text-end">
                            Prix Unitaire
                        </th>

                        <th class="text-end">
                            Valeur
                        </th>

                        <th class="text-center">
                            Statut
                        </th>

                        <th class="text-center">
                            Actions
                        </th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($stocks as $stock)

                        <tr class="
                            {{
                                $stock->quantite_disponible <= 0
                                    ? 'table-dark'
                                    : (
                                        $stock->quantite_disponible <= $stock->seuil_alerte
                                            ? 'table-danger'
                                            : ''
                                    )
                            }}
                        ">

                            {{-- CODE --}}
                            <td class="fw-semibold font-monospace">
                                {{ $stock->code_stock }}
                            </td>


                            {{-- PRODUIT --}}
                            <td>

                                <div class="fw-semibold">
                                    {{ $stock->produit->nom ?? 'Produit indisponible' }}
                                </div>

                                <small class="text-muted">
                                    {{ $stock->unite_mesure }}
                                </small>

                            </td>


                            {{-- QUANTITÉ --}}
                            <td class="text-end fw-semibold">

                                {{ number_format($stock->quantite_disponible, 2, ',', ' ') }}

                            </td>


                            {{-- PRIX --}}
                            <td class="text-end">

                                {{ number_format($stock->prix_unitaire, 0, ',', ' ') }} FCFA

                            </td>


                            {{-- VALEUR --}}
                            <td class="text-end fw-bold">

                                {{ number_format($stock->valeur_totale, 0, ',', ' ') }} FCFA

                            </td>


                            {{-- STATUT --}}
                            <td class="text-center">

                                @if($stock->quantite_disponible <= 0)

                                    <span class="badge bg-dark">
                                        Rupture
                                    </span>

                                @elseif($stock->quantite_disponible <= $stock->seuil_alerte)

                                    <span class="badge bg-danger">
                                        Alerte
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        Disponible
                                    </span>

                                @endif

                            </td>


                           {{-- ACTIONS --}}
<td class="text-center">
    <div class="btn-group d-flex justify-content-center gap-2" role="group">

        <a href="{{ route('inventaire.show', $stock->id) }}"
           class="btn btn-info btn-sm"
           title="Voir">
            <i class="bx bx-show"></i>
        </a>

        <a href="{{ route('inventaire.edit', $stock->id) }}"
           class="btn btn-warning btn-sm"
           title="Modifier">
            <i class="bx bx-edit"></i>
        </a>

        <form action="{{ route('inventaire.destroy', $stock->id) }}"
              method="POST"
              class="d-inline"
              onsubmit="return confirm('Supprimer ce stock ?')">

            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn btn-danger btn-sm"
                    title="Supprimer">
                <i class="bx bx-trash"></i>
            </button>

        </form>

    </div>
</td>
                    

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center py-5 text-muted">

                                Aucun stock disponible.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        <div class="mt-4 d-flex justify-content-end">

            {{ $stocks->links() }}

        </div>

    </div>

</div>

@endsection
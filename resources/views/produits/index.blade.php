@extends('layouts.app')

@section('title', 'Liste des Produits')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Liste des Produits
            </h3>

            <p class="text-muted mb-0">
                Gestion complète des produits recyclés et matières transformées
            </p>
        </div>

        <a href="{{ route('produits.create') }}"
           class="btn btn-primary shadow-sm">

            <i class="bx bx-plus"></i>
            Ajouter un Produit

        </a>

    </div>

    <!-- STATS -->
    <div class="row mb-4">

        <!-- TOTAL -->
        <div class="col-md-4 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Total Produits
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ $produits->count() }}
                            </h3>

                        </div>

                        <div class="bg-primary bg-opacity-10 p-3 rounded">

                            <i class="bx bx-package text-primary fs-3"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- PRODUITS ACTIFS -->
        <div class="col-md-4 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Produits Actifs
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ $produits->where('statut', 'actif')->count() }}
                            </h3>

                        </div>

                        <div class="bg-success bg-opacity-10 p-3 rounded">

                            <i class="bx bx-check-circle text-success fs-3"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- VALEUR STOCK -->
        <div class="col-md-4 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Valeur Totale
                            </p>

                            <h5 class="fw-bold mb-0">

                                {{ number_format($produits->sum(fn($p) => $p->quantite * $p->prix_unitaire), 0, ',', ' ') }}

                                FCFA

                            </h5>

                        </div>

                        <div class="bg-warning bg-opacity-10 p-3 rounded">

                            <i class="bx bx-money text-warning fs-3"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            @if(session('success'))

                <div class="alert alert-success border-0 shadow-sm">

                    <i class="bx bx-check-circle me-1"></i>

                    {{ session('success') }}

                </div>

            @endif

            <div class="table-responsive">

                <table class="table align-middle table-hover">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>
                            <th>Produit</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>Prix Unitaire</th>
                            <th>Statut</th>
                            <th>Tri Associé</th>
                            <th class="text-center">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($produits as $produit)

                            <tr>

                                <!-- ID -->
                                <td class="fw-semibold">
                                    #{{ $produit->id }}
                                </td>

                                <!-- NOM -->
                                <td>

                                    <div class="fw-semibold">
                                        {{ $produit->nom }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $produit->unite_mesure }}
                                    </small>

                                </td>

                                <!-- TYPE -->
                                <td>

                                    <span class="badge bg-info">

                                        {{ ucfirst($produit->type) }}

                                    </span>

                                </td>

                                <!-- QUANTITE -->
                                <td>

                                    <span class="fw-bold">

                                        {{ number_format($produit->quantite, 2, ',', ' ') }}

                                    </span>

                                    {{ $produit->unite_mesure }}

                                </td>

                                <!-- PRIX -->
                                <td>

                                    <span class="text-success fw-semibold">

                                        {{ number_format($produit->prix_unitaire, 0, ',', ' ') }}

                                        FCFA

                                    </span>

                                </td>

                                <!-- STATUT -->
                                <td>

                                    @if($produit->statut == 'actif')

                                        <span class="badge bg-success">
                                            Actif
                                        </span>

                                    @elseif($produit->statut == 'inactif')

                                        <span class="badge bg-secondary">
                                            Inactif
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Obsolète
                                        </span>

                                    @endif

                                </td>

                                <!-- TRI -->
                                <td>

                                    {{ $produit->trie->type_dechet ?? 'Non défini' }}

                                </td>

                                <!-- ACTIONS -->
                                <td>

                                    <div class="d-flex justify-content-center gap-2 flex-nowrap">

                                        <!-- VOIR -->
                                        <a href="{{ route('produits.show', $produit) }}"
                                           class="btn btn-sm btn-light border"
                                           title="Voir">

                                            <i class="bx bx-show"></i>

                                        </a>

                                        <!-- EDIT -->
                                        <a href="{{ route('produits.edit', $produit) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Modifier">

                                            <i class="bx bx-edit"></i>

                                        </a>

                                        <!-- DELETE -->
                                        <form action="{{ route('produits.destroy', $produit) }}"
                                              method="POST"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer ce produit ?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Supprimer">

                                                <i class="bx bx-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bx bx-package fs-1 d-block mb-2"></i>

                                        Aucun produit trouvé.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <!-- PAGINATION -->
            <div class="mt-4 d-flex justify-content-end">

                {{ $produits->links() }}

            </div>

        </div>

    </div>

</div>

@endsection
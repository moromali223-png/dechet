@extends('layouts.app')

@section('title', 'Produits Finis')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold">
                Produits Finis
            </h3>

            <p class="text-muted">
                Gestion des produits recyclés
            </p>
        </div>

        <a href="{{ route('agent.produits.create') }}"
           class="btn btn-primary">

            + Nouveau Produit

        </a>

    </div>

    <!-- STATS -->
    <div class="row mb-4">

        <div class="col-md-6">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Produits
                    </h6>

                    <h2 class="fw-bold">
                        {{ $stats['total_produits'] }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-muted">
                        Produits récents
                    </h6>

                    <h2 class="fw-bold">
                        {{ $stats['produits_recents'] }}
                    </h2>

                </div>

            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="card shadow-sm border-0">

        <div class="card-body table-responsive">

            <table class="table align-middle">

                <thead class="table-light">

                    <tr>

                        <th>#</th>

                        <th>Produit</th>

                        <th>Matière Première</th>

                        <th>Qualité</th>

                        <th>Quantité</th>

                        <th>Statut</th>

                        <th>Date</th>

                        <th width="180">Actions</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($produits as $produit)

                        <tr>

                            <td>
                                #{{ $produit->id }}
                            </td>

                            <td>

                                <strong>
                                    {{ $produit->nom }}
                                </strong>

                            </td>

                            <td>

                                {{ $produit->trie->type_dechet ?? '-' }}

                            </td>

                            <td>

                                <span class="badge bg-info">

                                    {{ $produit->trie->qualite ?? '-' }}

                                </span>

                            </td>

                            <td>

                                {{ $produit->quantite }}
                                {{ $produit->unite_mesure }}

                            </td>

                            <td>

                                <span class="badge bg-success">

                                    {{ $produit->statut }}

                                </span>

                            </td>

                            <td>

                                {{ $produit->created_at->format('d/m/Y') }}

                            </td>

                            <td>

                                <div class="d-flex gap-1">

                                    <a href="{{ route('agent.produits.show', $produit->id) }}"
                                       class="btn btn-info btn-sm">

                                        Voir

                                    </a>

                                    <a href="{{ route('agent.produits.edit', $produit->id) }}"
                                       class="btn btn-warning btn-sm">

                                        Edit

                                    </a>

                                    <form action="{{ route('agent.produits.destroy', $produit->id) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm">

                                            Supprimer

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center py-4">

                                Aucun produit trouvé.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

            <div class="mt-3">

                {{ $produits->links() }}

            </div>

        </div>

    </div>

</div>

@endsection
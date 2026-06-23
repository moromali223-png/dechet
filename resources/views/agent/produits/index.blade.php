@extends('agent.layouts.app')

@section('title', 'Produits Finis')

@section('content')

<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Produits Finis</h3>
            <p class="text-muted mb-0">Gestion des produits recyclés</p>
        </div>

        <a href="{{ route('agent.produits.create') }}"
           class="btn btn-primary shadow-sm rounded-3">
            <i class="bx bx-plus"></i>
            Nouveau Produit
        </a>
    </div>

    <!-- MESSAGE SUCCESS -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- STATISTIQUES -->
    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <small class="text-muted">Total Produits</small>
                        <h3 class="fw-bold mb-0">
                            {{ $stats['total_produits'] }}
                        </h3>
                    </div>

                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="bx bx-package fs-3 text-primary"></i>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <small class="text-muted">Produits récents</small>
                        <h3 class="fw-bold mb-0">
                            {{ $stats['produits_recents'] }}
                        </h3>
                    </div>

                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="bx bx-time-five fs-3 text-success"></i>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- TABLEAU -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Produit</th>
                           
                            <th>Unité</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($produits as $produit)

                            <tr>

                                <!-- ID -->
                                <td class="fw-bold text-primary">
                                    {{ $produit->id }}
                                </td>

                                <!-- IMAGE -->
                                <td>
                                    <img src="{{ $produit->photo_url }}"
                                         alt="{{ $produit->nom }}"
                                         width="70"
                                         height="70"
                                         class="rounded shadow-sm border"
                                         style="object-fit: cover;">
                                </td>

                                <!-- NOM -->
                                <td>
                                    <div class="fw-semibold">
                                        {{ $produit->nom }}
                                    </div>

                                    @if($produit->description)
                                        <small class="text-muted">
                                            {{ Str::limit($produit->description, 40) }}
                                        </small>
                                    @endif
                                </td>

                                <!-- TYPE
                                <td>
                                    <span class="badge bg-info px-3 py-2">
                                        {{ ucfirst($produit->type) }}
                                    </span>
                                </td> -->

                                <!-- UNITE -->
                                <td>
                                    {{ $produit->unite_mesure }}
                                </td>

                                <!-- PRIX -->
                                <td>
                                    <span class="fw-bold text-success">
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
                                    @else
                                        <span class="badge bg-secondary">
                                            Inactif
                                        </span>
                                    @endif
                                </td>

                                <!-- DATE -->
                                <td>
                                    {{ $produit->created_at->format('d/m/Y') }}
                                </td>

                                <!-- ACTIONS -->
                                <td>
                                    <div class="d-flex justify-content-center gap-2">

                                        <!-- Voir -->
                                        <a href="{{ route('agent.produits.show', $produit) }}"
                                           class="btn btn-sm btn-info text-white rounded-3"
                                           title="Voir">

                                            <i class="bx bx-show"></i>
                                        </a>

                                        <!-- Modifier -->
                                        <a href="{{ route('agent.produits.edit', $produit) }}"
                                           class="btn btn-sm btn-warning rounded-3"
                                           title="Modifier">

                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <!-- Supprimer -->
                                        <form action="{{ route('agent.produits.destroy', $produit) }}"
                                              method="POST"
                                              onsubmit="return confirm('Supprimer ce produit ?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger rounded-3">

                                                <i class="bx bx-trash"></i>
                                            </button>

                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9"
                                    class="text-center py-5 text-muted">

                                    <i class="bx bx-package fs-1 d-block mb-2"></i>

                                    Aucun produit disponible
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
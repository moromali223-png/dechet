@extends('layouts.app')

@section('title', 'Liste des Produits')

@section('content')

<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Liste des Produits</h3>
            <p class="text-muted mb-0">Gestion des produits recyclés</p>
        </div>

        <a href="{{ route('produits.create') }}" class="btn btn-primary shadow-sm rounded-3">
            <i class="bx bx-plus"></i> Ajouter un Produit
        </a>
    </div>

    <!-- STATS -->
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Produits</small>
                        <h3 class="fw-bold mb-0">{{ $produits->count() }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="bx bx-package fs-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Produits Actifs</small>
                        <h3 class="fw-bold mb-0">{{ $produits->where('statut','actif')->count() }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="bx bx-check-circle fs-3 text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Valeur Totale</small>
                        <h5 class="fw-bold mb-0">
                            {{ number_format($produits->sum(fn($p) => $p->quantite * $p->prix_unitaire), 0, ',', ' ') }} FCFA
                        </h5>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                        <i class="bx bx-money fs-3 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success shadow-sm border-0 rounded-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Produit</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($produits as $produit)

                            <tr>

                                <!-- ID -->
                                <td class="fw-bold text-primary">
                                    #{{ $produit->id }}
                                </td>

                                <!-- PHOTO -->
                                <!-- PHOTO dans le tableau -->
<td>
    <img src="{{ $produit->photo_url }}"
         class="rounded shadow-sm border"
         width="70"
         height="70"
         style="object-fit: cover;"
         alt="{{ $produit->nom }}">
</td>

                                <!-- NOM -->
                                <td>
                                    <div class="fw-semibold">{{ $produit->nom }}</div>
                                    <small class="text-muted">{{ $produit->unite_mesure }}</small>
                                </td>

                                <!-- TYPE -->
                                <td>
                                    <span class="badge bg-info px-3 py-2">
                                        {{ ucfirst($produit->type) }}
                                    </span>
                                </td>

                                <!-- QUANTITE -->
                                <td>
                                    <span class="fw-bold">
                                        {{ number_format($produit->quantite,2,',',' ') }}
                                    </span>
                                    {{ $produit->unite_mesure }}
                                </td>

                                <!-- PRIX -->
                                <td>
                                    <span class="text-success fw-bold">
                                        {{ number_format($produit->prix_unitaire,0,',',' ') }} FCFA
                                    </span>
                                </td>

                                <!-- STATUT -->
                                <td>
                                    @if($produit->statut == 'actif')
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>

                                <!-- ACTION -->
                                <td>
                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="{{ route('produits.show',$produit) }}"
                                           class="btn btn-sm btn-info text-white rounded-3"
                                           title="Voir">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        <a href="{{ route('produits.edit',$produit) }}"
                                           class="btn btn-sm btn-warning rounded-3"
                                           title="Modifier">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <form action="{{ route('produits.destroy',$produit) }}"
                                              method="POST"
                                              onsubmit="return confirm('Supprimer ce produit ?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger rounded-3">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bx bx-package fs-1 d-block mb-2"></i>
                                    Aucun produit disponible
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-4 d-flex justify-content-end">
                {{ $produits->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
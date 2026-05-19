@extends('layouts.app')

@section('title', 'Gestion des tout Produits')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestion des tout Produits</h5>
                    <a href="{{ route('admin.commandes.index') }}" class="btn btn-outline-primary">
                        <i class="bx bx-arrow-back me-1"></i>Commandes
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produits as $produit)
                                <tr>
                                    <td>
                                        @if($produit->photo)
                                        <img src="{{ asset('storage/' . $produit->photo) }}" alt="{{ $produit->nom }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                        @else
                                        <i class="bx bx-image-alt text-muted" style="font-size: 2rem;"></i>
                                        @endif
                                    </td>
                                    <td>{{ $produit->nom }}</td>
                                    <td>{{ $produit->categorie ?? 'N/A' }}</td>
                                    <td>{{ number_format($produit->prix_unitaire, 2) }} FCFA</td>
                                    <td>
                                        <span class="badge bg-info">{{ $produit->stock_disponible }}</span>
                                    </td>
                                    <td>
                                        @if($produit->statut === 'actif')
                                        <span class="badge bg-success">Actif</span>
                                        @else
                                        <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('produits.show', $produit) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('produits.edit', $produit) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bx bx-package display-1 text-muted"></i>
                                        <h5 class="mt-3">Aucun produit</h5>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $produits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
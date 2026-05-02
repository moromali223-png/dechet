@extends('layouts.app')

@section('title', 'Liste des Produits')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Liste des Produits</h4>
        <a href="{{ route('produits.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Ajouter un Produit
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Unité</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Statut</th>
                        <th>Tri</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produits as $produit)
                        <tr>
                            <td>{{ $produit->nom }}</td>
                            <td>{{ $produit->type }}</td>
                            <td>{{ $produit->unite_mesure }}</td>
                            <td>{{ $produit->quantite ?? 0 }}</td>
                            <td>{{ number_format($produit->prix_unitaire, 2, ',', ' ') }} FCFA</td>
                            <td>{{ ucfirst($produit->statut) }}</td>
                            <td>{{ $produit->trie->type_dechet ?? 'N/A' }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('produits.edit', $produit) }}" class="btn btn-sm btn-warning me-2" title="Modifier">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('produits.destroy', $produit) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce produit ?')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Aucun produit trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $produits->links() }}
        </div>
    </div>
</div>
@endsection

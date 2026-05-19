@extends('agent.layouts.app')

@section('title', 'Produits Finis')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Produits Finis</h3>
            <p class="text-muted mb-0">Gestion des produits recyclés</p>
        </div>

        <a href="{{ route('agent.produits.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Nouveau Produit
        </a>
    </div>

    <!-- STATS -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Produits</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['total_produits'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Produits récents</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['produits_recents'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Produit</th>
                        <th>Matière Première</th>
                        <th>Qualité</th>
                        <th>Quantité</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th class="text-center" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produits as $produit)
                        <tr>
                            <td><strong>#{{ $produit->id }}</strong></td>
                            <td>
                                <strong>{{ $produit->nom }}</strong>
                            </td>
                            <td>{{ $produit->trie->type_dechet ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $produit->trie->qualite ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $produit->quantite }} {{ $produit->unite_mesure }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-success">
                                    {{ ucfirst($produit->statut) }}
                                </span>
                            </td>
                            <td>{{ $produit->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    
                                    <!-- Voir -->
                                    <a href="{{ route('agent.produits.show', $produit->id) }}" 
                                       class="btn btn-icon btn-sm btn-info" 
                                       title="Voir détails">
                                        <i class="bx bx-eye"></i>
                                    </a>

                                    <!-- Modifier -->
                                    <a href="{{ route('agent.produits.edit', $produit->id) }}" 
                                       class="btn btn-icon btn-sm btn-warning" 
                                       title="Modifier">
                                        <i class="bx bx-edit"></i>
                                    </a>

                                    <!-- Supprimer -->
                                    <form action="{{ route('agent.produits.destroy', $produit->id) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        
                                        <button type="submit" 
                                                class="btn btn-icon btn-sm btn-danger"
                                                title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bx bx-package bx-md mb-2"></i><br>
                                Aucun produit trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $produits->links() }}
            </div>
        </div>
    </div>

</div>

@endsection
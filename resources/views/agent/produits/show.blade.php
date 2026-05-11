@extends('layouts.app')

@section('title', 'Détail Produit - ' . $produit->nom)

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <!-- HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">
                <i class="bx bx-package me-2"></i> {{ $produit->nom }}
            </h2>
            <p class="text-muted mb-0">Produit recyclé • Référence #{{ $produit->id }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('agent.produits.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Retour
            </a>
            <a href="{{ route('agent.produits.edit', $produit) }}" class="btn btn-warning">
                <i class="bx bx-edit"></i> Modifier
            </a>
        </div>
    </div>

    <div class="row">

        <!-- MAIN CONTENT -->
        <div class="col-lg-8">

            <!-- Informations principales -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bx bx-info-circle"></i> Informations du Produit
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">

                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bx bx-label fs-1 text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Nom du produit</small>
                                    <h5 class="mb-0 fw-semibold">{{ $produit->nom }}</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bx bx-category fs-1 text-info"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Type de déchet</small>
                                    <h5 class="mb-0">
                                        <span class="badge bg-info fs-6">{{ $produit->type ?? $produit->type_dechet ?? 'Non défini' }}</span>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Quantité produite</small>
                            <h4 class="fw-bold text-dark mb-0">
                                {{ number_format($produit->quantite ?? 0, 2) }} 
                                <small class="text-muted">{{ $produit->unite_mesure ?? 'kg' }}</small>
                            </h4>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Prix unitaire</small>
                            <h4 class="fw-bold text-success mb-0">
                                {{ number_format($produit->prix_unitaire ?? 0, 0, ',', ' ') }} FCFA
                            </h4>
                        </div>

                        <div class="col-12">
                            <small class="text-muted">Valeur Totale Estimée</small>
                            <h3 class="fw-bold text-success mb-0">
                                {{ number_format(($produit->quantite ?? 0) * ($produit->prix_unitaire ?? 0), 0, ',', ' ') }} FCFA
                            </h3>
                        </div>

                    </div>

                    <hr class="my-4">

                    <div>
                        <small class="text-muted d-block mb-2">Description</small>
                        <p class="mb-0 fs-6">
                            {{ $produit->description ?? '<span class="text-muted fst-italic">Aucune description fournie.</span>' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Matière Première / Tri associé -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0">
                        <i class="bx bx-recycle"></i> Matière Première Utilisée
                    </h5>
                </div>
                <div class="card-body">
                    @if($produit->trie)
                        <div class="row g-4">
                            <div class="col-sm-6 col-lg-4">
                                <small class="text-muted">ID Tri</small>
                                <h5>#{{ $produit->trie->id }}</h5>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <small class="text-muted">Type de Déchet</small>
                                <h5>{{ $produit->trie->type_dechet }}</h5>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <small class="text-muted">Quantité Triée</small>
                                <h5>{{ $produit->trie->quantite_trier }} {{ $produit->trie->unite }}</h5>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <small class="text-muted">Qualité du Tri</small>
                                <h5>
                                    <span class="badge @if($produit->trie->qualite == 'Excellent') bg-success @elseif($produit->trie->qualite == 'Bon') bg-info @else bg-warning @endif">
                                        {{ $produit->trie->qualite }}
                                    </span>
                                </h5>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <small class="text-muted">Destination</small>
                                <h5>{{ $produit->trie->destination ?? '—' }}</h5>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <small class="text-muted">Date du Tri</small>
                                <h5>{{ $produit->trie->created_at->format('d/m/Y H:i') }}</h5>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bx bx-error-circle"></i> Aucune matière première (tri) associée à ce produit.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">

            <!-- Statut -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center py-4">
                    <small class="text-muted">Statut actuel</small>
                    <div class="mt-3">
                        @if($produit->statut == 'termine')
                            <span class="badge bg-success fs-5 px-4 py-2">✅ Terminé</span>
                        @elseif($produit->statut == 'en_production')
                            <span class="badge bg-warning fs-5 px-4 py-2">⚙️ En production</span>
                        @else
                            <span class="badge bg-secondary fs-5 px-4 py-2">📦 Stocké</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions Rapides -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Actions rapides</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('agent.produits.edit', $produit) }}" class="btn btn-warning btn-lg">
                        <i class="bx bx-edit"></i> Modifier le produit
                    </a>

                    <form action="{{ route('agent.produits.destroy', $produit) }}" method="POST"
                          onsubmit="return confirm('Voulez-vous vraiment supprimer ce produit ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-lg w-100">
                            <i class="bx bx-trash"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <!-- Informations système -->
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Informations système</h6>
                </div>
                <div class="card-body small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Créé le</span>
                        <span class="fw-medium">{{ $produit->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Dernière modification</span>
                        <span class="fw-medium">{{ $produit->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
@extends('agent.layouts.app')

@section('title', 'Détail du Produit')

@section('content')

<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm rounded-4">

        <!-- Header -->
        <div class="card-header bg-primary text-white rounded-top-4 py-3">
            <div class="d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    <i class="bx bx-package me-2"></i>
                    Détail du Produit
                </h4>

                <a href="{{ route('agent.produits.index') }}"
                   class="btn btn-light btn-sm">
                    <i class="bx bx-arrow-back"></i>
                    Retour à la liste
                </a>

            </div>
        </div>

        <div class="card-body p-5">

            <div class="row g-5">

                <!-- Photo -->
                <div class="col-md-5">

                    <div class="text-center">

                        <img src="{{ $produit->photo_url }}"
                             alt="{{ $produit->nom }}"
                             class="img-fluid rounded-4 shadow-sm border"
                             style="max-height:380px; object-fit:cover; width:100%;">

                    </div>

                </div>

                <!-- Informations -->
                <div class="col-md-7">

                    <h2 class="fw-bold mb-1">
                        {{ $produit->nom }}
                    </h2>

                    <p class="text-muted fs-5 mb-4">
                        {{ ucfirst($produit->type) }}
                    </p>

                    <!-- Informations principales -->
                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="text-muted small">
                                Identifiant
                            </label>

                            <h5 class="fw-bold">
                                {{ $produit->id }}
                            </h5>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">
                                Unité de mesure
                            </label>

                            <h5 class="fw-bold">
                                {{ $produit->unite_mesure }}
                            </h5>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">
                                Prix Unitaire
                            </label>

                            <h4 class="fw-bold text-success">
                                {{ number_format($produit->prix_unitaire, 0, ',', ' ') }}
                                FCFA
                            </h4>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">
                                Statut
                            </label>

                            <div>
                                @if($produit->statut == 'actif')

                                    <span class="badge bg-success px-3 py-2">
                                        Actif
                                    </span>

                                @else

                                    <span class="badge bg-secondary px-3 py-2">
                                        Inactif
                                    </span>

                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- Description -->
                    <div class="mt-5">

                        <h5 class="fw-bold border-bottom pb-2">
                            Description
                        </h5>

                        @if($produit->description)

                            <p class="text-muted lh-lg mt-3">
                                {{ $produit->description }}
                            </p>

                        @else

                            <p class="text-muted fst-italic mt-3">
                                Aucune description disponible.
                            </p>

                        @endif

                    </div>

                    <!-- Informations système -->
                    <div class="mt-5">

                        <h5 class="fw-bold border-bottom pb-2">
                            Informations système
                        </h5>

                        <div class="row mt-3">

                            <div class="col-md-6">
                                <small class="text-muted">
                                    Créé le
                                </small>

                                <div class="fw-semibold">
                                    {{ $produit->created_at->format('d/m/Y à H:i') }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">
                                    Dernière modification
                                </small>

                                <div class="fw-semibold">
                                    {{ $produit->updated_at->format('d/m/Y à H:i') }}
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- Actions -->
                    <div class="mt-5 d-flex gap-2">

                        <a href="{{ route('agent.produits.edit', $produit) }}"
                           class="btn btn-warning">

                            <i class="bx bx-edit"></i>
                            Modifier

                        </a>

                        <a href="{{ route('agent.produits.index') }}"
                           class="btn btn-secondary">

                            <i class="bx bx-arrow-back"></i>
                            Retour

                        </a>

                        <form action="{{ route('agent.produits.destroy', $produit) }}"
                              method="POST"
                              onsubmit="return confirm('Supprimer ce produit ?')">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger">

                                <i class="bx bx-trash"></i>
                                Supprimer

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
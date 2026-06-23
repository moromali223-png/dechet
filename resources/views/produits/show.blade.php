@extends('layouts.app')

@section('title', 'Détail du Produit')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-4">
        
        <!-- Header -->
        <div class="card-header bg-info text-white rounded-top-4 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bx bx-package me-2"></i>
                    Détail du Produit
                </h4>
                <a href="{{ route('produits.index') }}" class="btn btn-light btn-sm">
                    <i class="bx bx-arrow-back"></i> Retour à la liste
                </a>
            </div>
        </div>

        <div class="card-body p-5">
            <div class="row g-5">

                <!-- Photo -->
                <div class="col-md-5 text-center">
                    <img src="{{ $produit->photo_url }}" 
                         class="img-fluid rounded-4 shadow-sm"
                         style="max-height: 380px; object-fit: cover; width: 100%;"
                         alt="{{ $produit->nom }}">
                </div>

                <!-- Informations -->
                <div class="col-md-7">

                    <!-- Nom + Type -->
                    <h2 class="fw-bold mb-1">{{ $produit->nom }}</h2>
                    <p class="text-muted fs-5 mb-4">{{ $produit->type ?? 'Non défini' }}</p>

                  

                    <div class="row g-4">

                

                        <div class="col-6 col-lg-4">
                            <label class="text-muted small">Prix Unitaire</label>
                            <h4 class="mb-0 text-success">
                                {{ number_format($produit->prix_unitaire ?? 0, 2) }} <small>FCFA</small>
                            </h4>
                        </div>

                        <div class="col-6 col-lg-4">
                            <label class="text-muted small">Statut</label>
                            <h5>
                                @if($produit->statut == 'actif')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </h5>
                        </div>

                    </div>

                    <!-- Description -->
                    @if($produit->description)
                    <div class="mt-5">
                        <h5 class="fw-bold border-bottom pb-2">Description</h5>
                        <p class="text-muted lh-base">
                            {{ $produit->description }}
                        </p>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="mt-5">
                        <a href="{{ route('produits.edit', $produit) }}" 
                           class="btn btn-warning me-2">
                            <i class="bx bx-edit"></i> Modifier
                        </a>
                        <a href="{{ route('produits.index') }}" 
                           class="btn btn-secondary">
                            Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
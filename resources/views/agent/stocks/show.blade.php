@extends('agent.layouts.app')

@section('title', 'Détail Stock - ' . ($stock->produit->nom ?? 'Produit'))

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Détail du Stock</h4>
            <p class="text-muted mb-0">Suivi complet du produit en stock</p>
        </div>

        <a href="{{ route('agent.stocks.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Retour
        </a>
    </div>

    <div class="row g-4">

        <!-- Carte principale -->
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-center mb-4">
                        <div class="avatar avatar-xl mx-auto mb-3">
                            <span class="avatar-initial rounded-circle bg-label-primary">
                                <i class="bx bx-package fs-2"></i>
                            </span>
                        </div>

                        <h4 class="mb-1">{{ $stock->produit->nom ?? 'Produit inconnu' }}</h4>
                        <small class="text-muted">{{ $stock->code_stock }}</small>
                    </div>

                    <!-- Etat -->
                    <div class="text-center mb-4">
                        @if($stock->quantite_disponible <= $stock->seuil_alerte)
                            <span class="badge bg-danger fs-6 px-3 py-2">
                                <i class="bx bx-error-circle me-1"></i> Stock faible
                            </span>
                        @else
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="bx bx-check-circle me-1"></i> Disponible
                            </span>
                        @endif
                    </div>

                    <!-- Infos -->
                    <div class="list-group list-group-flush">

                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Quantité</span>
                            <strong>{{ number_format($stock->quantite_disponible, 2, ',', ' ') }}</strong>
                        </div>

                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Prix unitaire</span>
                            <strong>{{ number_format($stock->prix_unitaire, 0, ',', ' ') }} FCFA</strong>
                        </div>

                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Valeur totale</span>
                            <strong class="text-primary">
                                {{ number_format($stock->quantite_disponible * $stock->prix_unitaire, 0, ',', ' ') }} FCFA
                            </strong>
                        </div>

                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Seuil alerte</span>
                            <strong>{{ $stock->seuil_alerte }}</strong>
                        </div>

                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Créé le</span>
                            <strong>{{ $stock->created_at->format('d/m/Y') }}</strong>
                        </div>

                    </div>

                </div>
            </div>

        </div>

        <!-- Historique -->
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-semibold">
                            <i class="bx bx-transfer-alt me-1"></i>
                            Historique des mouvements
                        </h5>
                        <small class="text-muted">Entrées / sorties du produit</small>
                    </div>
                </div>

                <div class="card-body">

                    @if($historique->isNotEmpty())

                        <div class="timeline">

                            @foreach($historique as $mouvement)

                                @php
                                    $color = match($mouvement->type_mouvement) {
                                        'entree' => 'success',
                                        'sortie' => 'danger',
                                        default => 'warning'
                                    };

                                    $icon = match($mouvement->type_mouvement) {
                                        'entree' => 'bx-plus-circle',
                                        'sortie' => 'bx-minus-circle',
                                        default => 'bx-refresh'
                                    };
                                @endphp

                                <div class="d-flex mb-4">

                                    <div class="me-3">
                                        <span class="avatar avatar-sm">
                                            <span class="avatar-initial rounded-circle bg-label-{{ $color }}">
                                                <i class="bx {{ $icon }}"></i>
                                            </span>
                                        </span>
                                    </div>

                                    <div class="flex-grow-1">

                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1">
                                                {{ ucfirst($mouvement->type_mouvement) }}
                                            </h6>

                                            <small class="text-muted">
                                                {{ $mouvement->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>

                                        <p class="mb-1 text-muted small">
                                            {{ $mouvement->description ?? 'Aucune observation' }}
                                        </p>

                                        <span class="fw-bold text-{{ $color }}">
                                            {{ $mouvement->type_mouvement === 'entree' ? '+' : '-' }}
                                            {{ number_format($mouvement->quantite, 2, ',', ' ') }}
                                        </span>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center py-5">
                            <i class="bx bx-package bx-lg text-muted mb-3"></i>
                            <h6 class="text-muted">Aucun mouvement trouvé</h6>
                            <p class="text-muted small mb-0">
                                Aucun historique enregistré pour ce produit.
                            </p>
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>
@endsection
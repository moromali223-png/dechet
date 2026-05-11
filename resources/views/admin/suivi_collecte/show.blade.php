@extends('layouts.app')

@section('title', 'Détails de la collecte')

@section('content')

<div class="container-fluid py-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                📦 Détails de la collecte
            </h3>

            <p class="text-muted mb-0">
                Informations complètes sur la planification, le client,
                le collecteur et la collecte effectuée.
            </p>
        </div>

        <a href="{{ route('suivi_collecte.index') }}"
           class="btn btn-outline-secondary">
            ← Retour à la liste
        </a>

    </div>

    <div class="row g-4">

        <!-- CONTENU PRINCIPAL -->
        <div class="col-lg-8">

            <!-- PLANIFICATION -->
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        📋 Informations de la planification
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">
                                Code de planification
                            </label>

                            <div class="fw-bold fs-5">
                                {{ $planification->code_planification ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">
                                Statut
                            </label>

                            <div>
                                <span class="badge px-3 py-2 bg-{{ $planification->statut === 'terminee' ? 'success' : 'warning' }}">
                                    {{ ucfirst(str_replace('_', ' ', $planification->statut)) }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">
                                Zone
                            </label>

                            <div class="fw-semibold">
                                {{ optional($planification->zone)->nom ?? 'Non définie' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">
                                Date prévue
                            </label>

                            <div class="fw-semibold">
                                {{ $planification->date_prevue?->format('d/m/Y à H:i') ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">
                                Type de collecte
                            </label>

                            <div>
                                {{ $planification->type_collecte ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">
                                Priorité
                            </label>

                            <div>
                                {{ $planification->priorite ?? '—' }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- CLIENT -->
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        👤 Informations du client
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">
                                Nom complet
                            </label>

                            <div class="fw-semibold">
                                {{ optional($planification->client?->user)->name ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">
                                Adresse email
                            </label>

                            <div>
                                {{ optional($planification->client?->user)->email ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">
                                Téléphone
                            </label>

                            <div>
                                {{ $planification->client?->telephone ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">
                                Quartier
                            </label>

                            <div>
                                {{ $planification->client?->quartier ?? '—' }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- COLLECTEUR -->
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        🚛 Informations du collecteur
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">
                                Nom du collecteur
                            </label>

                            <div class="fw-semibold">
                                {{ optional(optional($planification->collecteur)->user)->name ?? 'Non assigné' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">
                                Email
                            </label>

                            <div>
                                {{ optional(optional($planification->collecteur)->user)->email ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">
                                Téléphone
                            </label>

                            <div>
                                {{ $planification->collecteur?->telephone ?? '—' }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">

            <!-- PHOTO -->
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        📸 Preuve de collecte
                    </h5>
                </div>

                <div class="card-body text-center">

                    @if($planification->collecte?->photo)

                        <img
                            src="{{ asset('storage/' . $planification->collecte->photo) }}"
                            alt="Photo collecte"
                            class="img-fluid rounded shadow-sm"
                            style="max-height: 350px; object-fit: cover;"
                        >

                    @else

                        <div class="py-5 text-muted">
                            <i class="fas fa-image fa-3x mb-3"></i>

                            <p class="mb-0">
                                Aucune photo disponible
                            </p>
                        </div>

                    @endif

                </div>

            </div>

            <!-- DETAILS -->
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        ♻️ Détails de la collecte
                    </h5>
                </div>

                <div class="card-body p-0">

                    <table class="table table-striped align-middle mb-0">

                        <tr>
                            <th class="ps-3">Statut</th>

                            <td>
                                {{ ucfirst(str_replace('_', ' ', $planification->statut)) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="ps-3">Date collecte</th>

                            <td>
                                {{ $planification->collecte?->created_at?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                        </tr>

                        <tr>
                            <th class="ps-3">Observation</th>

                            <td>
                                {{ $planification->collecte?->observation ?? 'Aucune observation' }}
                            </td>
                        </tr>

                        <tr>
                            <th class="ps-3">Ordre passage</th>

                            <td>
                                {{ $planification->ordre_passage ?? '—' }}
                            </td>
                        </tr>

                        <tr>
                            <th class="ps-3">Durée estimée</th>

                            <td>
                                {{ $planification->duree_estimee ?? '—' }} min
                            </td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
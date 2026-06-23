@extends('layouts.app')

@section('title', 'Ajouter un tri')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">
        <h4 class="mb-1 fw-bold">
            Ajouter un tri
        </h4>
        <small class="text-muted">
            Enregistrer une opération de tri des déchets
        </small>
    </div>

    <div class="card-body">

        {{-- ERREURS --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        <form action="{{ route('tries.store') }}" method="POST">
            @csrf

            <div class="row g-3">

                {{-- ================= INFO TRI ================= --}}
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-recycle me-2"></i>
                        Informations du tri
                    </h5>
                </div>

                {{-- Type de déchet --}}
                <div class="col-md-6">
                    <label class="form-label">Type de déchet</label>
                    <select name="type_dechet"
                            class="form-select"
                            required>
                        <option value="">-- Sélectionner --</option>
                        <option value="Plastique">Plastique</option>
                        <option value="Métal">Métal</option>
                        <option value="Papier/Carton">Papier / Carton</option>
                        <option value="Verre">Verre</option>
                        <option value="Organique">Organique</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>

                {{-- Quantité --}}
                <div class="col-md-3">
                    <label class="form-label">Quantité</label>
                    <input type="number"
                           step="0.01"
                           name="quantite_trier"
                           class="form-control"
                           required>
                </div>

                {{-- Unité --}}
                <div class="col-md-3">
                    <label class="form-label">Unité</label>
                    <select name="unite" class="form-select" required>
                        <option value="kg">kg</option>
                        <option value="g">g</option>
                        <option value="T">T</option>
                        <option value="L">L</option>
                    </select>
                </div>

                <hr class="my-4">

                {{-- ================= PESA GE ================= --}}
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-weight me-2"></i>
                        Pesage associé
                    </h5>
                </div>

                <div class="col-12">
                    <label class="form-label">Pesage</label>
                    <select name="pesage_id" class="form-select" required>
                        <option value="">-- Sélectionner un pesage --</option>
                        @foreach($pesages as $pesage)
                            <option value="{{ $pesage->id }}">
                                Pesage #{{ $pesage->id }} —
                                {{ number_format($pesage->poids, 2) }} {{ $pesage->unite ?? 'kg' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr class="my-4">

                {{-- ================= QUALITÉ ================= --}}
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-check-circle me-2"></i>
                        Qualité et traitement
                    </h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Qualité du tri</label>
                    <select name="qualite" class="form-select" required>
                        <option value="Excellent">Excellent</option>
                        <option value="Bon">Bon</option>
                        <option value="Moyen">Moyen</option>
                        <option value="Mauvais">Mauvais</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Destination</label>
                    <select name="destination" class="form-select">
                        <option value="">-- Sélectionner --</option>
                        <option value="Recyclé">Recyclé</option>
                        <option value="Revendu">Revendu</option>
                        <option value="Stocké">Stocké</option>
                        <option value="Déchet final">Déchet final</option>
                    </select>
                </div>

                <hr class="my-4">

                {{-- ================= VALEUR ================= --}}
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-money me-2"></i>
                        Informations financières
                    </h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Valeur estimée (FCFA)</label>
                    <input type="number"
                           step="0.01"
                           name="valeur_estimee"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Notes</label>
                    <textarea name="notes"
                              rows="2"
                              class="form-control"
                              placeholder="Observations sur le tri..."></textarea>
                </div>

            </div>

            {{-- BOUTONS --}}
            <div class="mt-4 d-flex justify-content-end gap-2">

                <a href="{{ route('tries.index') }}"
                   class="btn btn-light">
                    Annuler
                </a>

                <button type="submit"
                        class="btn btn-primary">
                    <i class="bx bx-save me-1"></i>
                    Enregistrer le tri
                </button>

            </div>

        </form>

    </div>
</div>

@endsection
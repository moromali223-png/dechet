@extends('layouts.app')

@section('title', 'Créer une planification')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">
        <h4 class="mb-1 fw-bold">
            Créer une planification
        </h4>
        <small class="text-muted">
            Planifier une tournée de collecte des déchets
        </small>
    </div>

    <div class="card-body">

        {{-- ERREURS --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        <form action="{{ route('planifications.store') }}" method="POST">
            @csrf

            <div class="row g-3">

                {{-- ================= INFORMATIONS GÉNÉRALES ================= --}}
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-calendar me-2"></i>
                        Informations de planification
                    </h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Code planification</label>
                    <input type="text"
                           name="code_planification"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nom tournée</label>
                    <input type="text"
                           name="nom_tournee"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jour de la semaine</label>
                    <select name="jour_semaine"
                            class="form-select"
                            required>
                        <option value="">-- Sélectionner --</option>
                        <option value="lundi">Lundi</option>
                        <option value="mardi">Mardi</option>
                        <option value="mercredi">Mercredi</option>
                        <option value="jeudi">Jeudi</option>
                        <option value="vendredi">Vendredi</option>
                        <option value="samedi">Samedi</option>
                        <option value="dimanche">Dimanche</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date prévue</label>
                    <input type="date"
                           name="date_prevue"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Période</label>
                    <select name="periode"
                            class="form-select"
                            required>
                        <option value="">-- Sélectionner --</option>
                        <option value="HEBDOMADAIRE">Hebdomadaire</option>
                        <option value="BI_HEBDOMADAIRE">Bi-hebdomadaire</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Type de collecte</label>
                    <select name="type_collecte"
                            class="form-select"
                            required>
                        <option value="">-- Sélectionner --</option>
                        <option value="plastique">Plastique</option>
                        <option value="organique">Organique</option>
                        <option value="papier">Papier</option>
                        <option value="metal">Métal</option>
                        <option value="electronique">Électronique</option>
                        <option value="verre">Verre</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Statut</label>
                    <select name="statut"
                            class="form-select"
                            required>
                        <option value="planifiee">Planifiée</option>
                        <option value="assignee">Affectée</option>
                        <option value="en_route">En route</option>
                        <option value="en_cours">En cours</option>
                        <option value="terminee">Terminée</option>
                        <option value="annulee">Annulée</option>
                        <option value="reportee">Reportée</option>
                    </select>
                </div>

                <hr class="my-4">

                {{-- ================= AFFECTATIONS ================= --}}
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-user-check me-2"></i>
                        Affectations
                    </h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Zone</label>
                    <select name="zone_id" class="form-select">
                        <option value="">-- Sélectionner une zone --</option>
                        @foreach($zones ?? [] as $zone)
                            <option value="{{ $zone->id }}">
                                {{ $zone->nom_zone ?? $zone->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Agent</label>
                    <select name="agent_id" class="form-select">
                        <option value="">-- Sélectionner un agent --</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Collecteur</label>
                    <select name="collecteur_id" class="form-select">
                        <option value="">-- Sélectionner un collecteur --</option>
                        @foreach($collecteurs ?? [] as $collecteur)
                            <option value="{{ $collecteur->id }}">
                                {{ optional($collecteur)->name ?? ('Collecteur #' . $collecteur->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Abonnement</label>
                    <select name="abonnement_id" class="form-select">
                        <option value="">Aucun</option>
                        @foreach($abonnements as $abonnement)
                            <option value="{{ $abonnement->id }}">
                                #{{ $abonnement->id }} - {{ $abonnement->type_abonnement }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Déclaration</label>
                    <select name="declaration_id" class="form-select">
                        <option value="">Aucune</option>
                        @foreach($declarations as $declaration)
                            <option value="{{ $declaration->id }}">
                                Déclaration #{{ $declaration->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr class="my-4">

                {{-- ================= PARAMÈTRES ================= --}}
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-cog me-2"></i>
                        Paramètres de tournée
                    </h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ordre de passage</label>
                    <input type="number"
                           name="ordre_passage"
                           class="form-control"
                           min="1"
                           value="1">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Durée estimée (min)</label>
                    <input type="number"
                           name="duree_estimee"
                           class="form-control"
                           min="15"
                           value="60">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Priorité</label>
                    <input type="number"
                           name="priorite"
                           class="form-control"
                           min="1"
                           max="5"
                           value="1">
                </div>

            </div>

            {{-- BOUTONS --}}
            <div class="mt-4 d-flex justify-content-end gap-2">

                <a href="{{ route('planifications.index') }}"
                   class="btn btn-light">
                    Annuler
                </a>

                <button type="submit"
                        class="btn btn-primary">
                    <i class="bx bx-save me-1"></i>
                    Créer la planification
                </button>

            </div>

        </form>

    </div>
</div>

@endsection
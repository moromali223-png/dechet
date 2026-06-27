@extends('layouts.app')

@section('title', 'Créer un abonnement')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">
        <h4 class="mb-1 fw-bold">
            Créer un abonnement
        </h4>
        <small class="text-muted">
            Enregistrer un nouvel abonnement de collecte des déchets
        </small>
    </div>

    <div class="card-body">

        {{-- Affichage des erreurs --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>
            </div>
        @endif

        <form action="{{ route('abonnements.store') }}" method="POST">
            @csrf

            <div class="row g-3">

                {{-- ==================== CLIENT ==================== --}}
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-user me-2"></i>
                        Informations du client
                    </h5>
                </div>

                @if(auth()->user()->role === 'admin')
                    <div class="col-md-6">
                        <label class="form-label">
                            Client
                        </label>

                        <select
                                id="client-select"
                                name="client_id"
                                class="form-select @error('client_id') is-invalid @enderror"
                                required
                            >
                            <option value="">
                                -- Sélectionner un client --
                            </option>

                            @foreach($clients as $client)
                                <option
                                    value="{{ $client->id }}"
                                    {{ old('client_id') == $client->id ? 'selected' : '' }}
                                >
                                    {{ $client->user->name ?? 'Client' }}
                                </option>
                            @endforeach

                        </select>

                        @error('client_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @else
                    <input type="hidden"
                           name="client_id"
                           value="{{ auth()->id() }}">
                @endif

                <hr class="my-4">

                {{-- ==================== ABONNEMENT ==================== --}}
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-package me-2"></i>
                        Informations de l'abonnement
                    </h5>
                </div>

                {{-- Type abonnement --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Type d'abonnement
                    </label>

                    <select
                        name="type_abonnement"
                        class="form-select @error('type_abonnement') is-invalid @enderror"
                        required
                    >
                        <option value="">
                            -- Sélectionner --
                        </option>

                        <option value="hebdomadaire">
                            Abonnement Hebdomadaire
                        </option>

                        <option value="mensuel">
                            Abonnement Mensuel
                        </option>

                        <option value="annuel">
                            Abonnement Annuel
                        </option>

                        <option value="premium">
                            Abonnement Premium
                        </option>
                    </select>
                </div>

                {{-- Type déchet --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Type de déchet
                    </label>

                    <select
                        name="type_dechet"
                        class="form-select @error('type_dechet') is-invalid @enderror"
                        required
                    >
                        <option value="">
                            -- Sélectionner --
                        </option>

                        <option value="plastique">Plastique</option>
                        <option value="papier">Papier / Carton</option>
                        <option value="metal">Métal</option>
                        <option value="verre">Verre</option>
                        <option value="organique">Déchets Organiques</option>
                        <option value="electronique">Déchets Électroniques</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>

                {{-- Fréquence --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Fréquence
                    </label>

                    <select
                        name="frequence"
                        id="frequence"
                        class="form-select"
                        required
                    >
                        <option value="">
                            -- Sélectionner --
                        </option>

                        <option value="hebdomadaire">
                            Hebdomadaire
                        </option>

                        <option value="mensuelle">
                            Mensuelle
                        </option>
                    </select>
                </div>

                {{-- Jour collecte --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Jour de collecte
                    </label>

                   <select name="jour_collecte" id="jour_collecte" class="form-select" required>
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

                {{-- Poids --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Poids estimé (kg)
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="poids_estime"
                        value="{{ old('poids_estime') }}"
                        class="form-control"
                        required
                    >
                </div>

                {{-- Montant --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Montant (FCFA)
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="montant"
                        value="{{ old('montant') }}"
                        class="form-control"
                    >
                </div>

                <hr class="my-4">

                {{-- ==================== PLANIFICATION ==================== --}}
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-calendar me-2"></i>
                        Planification de la collecte
                    </h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Date de début
                    </label>

                    <input
                        type="date"
                        name="date_debut"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Date de fin
                    </label>

                    <input
                        type="date"
                        name="date_fin"
                        class="form-control"
                        required
                    >
                </div>

                <hr class="my-4">

                {{-- ==================== ADRESSE ==================== --}}
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-map me-2"></i>
                        Adresse de collecte
                    </h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rue</label>
                    <input type="text" id="rue" name="rue" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Quartier</label>
                    <input type="text" id="quartier" name="quartier" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Porte</label>
                    <input type="text" id="porte" name="porte" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Repère</label>

                    <textarea
                        id="repere"
                        name="repere"
                        rows="2"
                        class="form-control"
                    ></textarea>
                </div>

            </div>

            {{-- Boutons --}}
            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('abonnements.index') }}"
                   class="btn btn-light">
                    Annuler
                </a>

                <button type="submit"
                        class="btn btn-primary">
                    <i class="bx bx-save me-1"></i>
                    Enregistrer l'abonnement
                </button>
            </div>

        </form>

    </div>
</div>

@endsection

@if(auth()->user()->role === 'admin')
    @php
        $clients_map = $clients->mapWithKeys(function($c) {
            return [$c->id => [
                'rue' => $c->rue ?? '',
                // fallback to user->address when client->quartier is empty
                'quartier' => $c->quartier ?? ($c->user->address ?? ''),
                'porte' => $c->porte ?? '',
                'repere' => $c->repere ?? '',
            ]];
        });
    @endphp

    <script>
        (function () {
            const clients = @json($clients_map);

            const select = document.getElementById('client-select');
            if (! select) return;

            const fill = (id) => {
                const data = clients[id] || {};
                document.getElementById('rue').value = data.rue || '';
                document.getElementById('quartier').value = data.quartier || '';
                document.getElementById('porte').value = data.porte || '';
                document.getElementById('repere').value = data.repere || '';
            };

            select.addEventListener('change', function (e) {
                fill(this.value);
            });

            // If old input present (validation fail), prefill
            document.addEventListener('DOMContentLoaded', function () {
                const initial = select.value || '{{ old('client_id', '') }}';
                if (initial) fill(initial);
            });
        })();
    </script>
@endif
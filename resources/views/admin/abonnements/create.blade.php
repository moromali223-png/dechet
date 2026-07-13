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
                        <label class="form-label">Client</label>
                        <select
                            id="client-select"
                            name="client_id"
                            class="form-select @error('client_id') is-invalid @enderror"
                            required
                        >
                            <option value="">-- Sélectionner un client --</option>

                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @else
                    <input type="hidden" name="client_id" value="{{ auth()->id() }}">
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
                    <label class="form-label">Type d'abonnement</label>
                    <select name="type_abonnement" class="form-select @error('type_abonnement') is-invalid @enderror" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="hebdomadaire" {{ old('type_abonnement') === 'hebdomadaire' ? 'selected' : '' }}>Abonnement Hebdomadaire</option>
                        <option value="mensuel" {{ old('type_abonnement') === 'mensuel' ? 'selected' : '' }}>Abonnement Mensuel</option>
                        <option value="annuel" {{ old('type_abonnement') === 'annuel' ? 'selected' : '' }}>Abonnement Annuel</option>
                        <option value="premium" {{ old('type_abonnement') === 'premium' ? 'selected' : '' }}>Abonnement Premium</option>
                    </select>
                </div>

                {{-- Type déchet --}}
                <div class="col-md-6">
                    <label class="form-label">Type de déchet</label>
                    <select name="type_dechet" class="form-select @error('type_dechet') is-invalid @enderror" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="plastique" {{ old('type_dechet') === 'plastique' ? 'selected' : '' }}>Plastique</option>
                        <option value="papier" {{ old('type_dechet') === 'papier' ? 'selected' : '' }}>Papier / Carton</option>
                        <option value="metal" {{ old('type_dechet') === 'metal' ? 'selected' : '' }}>Métal</option>
                        <option value="verre" {{ old('type_dechet') === 'verre' ? 'selected' : '' }}>Verre</option>
                        <option value="organique" {{ old('type_dechet') === 'organique' ? 'selected' : '' }}>Déchets Organiques</option>
                        <option value="electronique" {{ old('type_dechet') === 'electronique' ? 'selected' : '' }}>Déchets Électroniques</option>
                        <option value="autre" {{ old('type_dechet') === 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                {{-- Fréquence --}}
                <div class="col-md-6">
                    <label class="form-label">Fréquence</label>
                    <select name="frequence" id="frequence" class="form-select @error('frequence') is-invalid @enderror" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="hebdomadaire" {{ old('frequence') === 'hebdomadaire' ? 'selected' : '' }}>Hebdomadaire</option>
                        <option value="mensuelle" {{ old('frequence') === 'mensuelle' ? 'selected' : '' }}>Mensuelle</option>
                    </select>
                </div>

              {{-- Jour de collecte --}}
<div class="col-md-6">

    <label class="form-label">
        Jour de collecte
    </label>

    {{-- Champ réellement envoyé --}}
    <input
        type="hidden"
        name="jour_collecte"
        id="jour_collecte"
        value="{{ old('jour_collecte') }}"
    >

    {{-- Sélecteur Hebdomadaire --}}
    <select id="jour_hebdo"
            class="form-select">

        <option value="">-- Jour de la semaine --</option>

        @foreach(\App\Models\Abonnement::WEEK_DAYS as $jour)
            <option value="{{ $jour }}"
                {{ old('jour_collecte') == $jour ? 'selected' : '' }}>
                {{ ucfirst($jour) }}
            </option>
        @endforeach

    </select>

    {{-- Sélecteur Mensuel --}}
    <select id="jour_mensuel"
            class="form-select d-none">

        <option value="">-- Jour du mois --</option>

        @for($i=1;$i<=28;$i++)

            <option value="{{ $i }}"
                {{ old('jour_collecte') == $i ? 'selected' : '' }}>
                {{ $i }}
            </option>

        @endfor

    </select>

    @error('jour_collecte')
        <div class="text-danger mt-1">
            {{ $message }}
        </div>
    @enderror

</div>
                {{-- Poids --}}
                <div class="col-md-6">
                    <label class="form-label">Poids estimé (kg)</label>
                    <input type="number" step="0.01" min="0" name="poids_estime"
                           value="{{ old('poids_estime') }}" class="form-control" required>
                </div>

                {{-- Montant --}}
                <div class="col-md-6">
                    <label class="form-label">Montant (FCFA)</label>
                    <input type="number" step="0.01" min="0" name="montant"
                           value="{{ old('montant') }}" class="form-control">
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
                    <label class="form-label">Date de début</label>
                    <input type="date" name="date_debut" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date de fin</label>
                    <input type="date" name="date_fin" class="form-control" required>
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
                    <input type="text" id="rue" name="rue" class="form-control" value="{{ old('rue') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Quartier</label>
                    <input type="text" id="quartier" name="quartier" class="form-control" value="{{ old('quartier') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Porte</label>
                    <input type="text" id="porte" name="porte" class="form-control" value="{{ old('porte') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Repère</label>
                    <textarea id="repere" name="repere" rows="2" class="form-control">{{ old('repere') }}</textarea>
                </div>

            </div>

            {{-- Boutons --}}
            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('abonnements.index') }}" class="btn btn-light">Annuler</a>
                <button type="submit" class="btn btn-primary">
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
                'rue'      => $c->rue ?? '',
                'quartier' => $c->quartier ?? ($c->address ?? ''),
                'porte'    => $c->porte ?? '',
                'repere'   => $c->repere ?? '',
            ]];
        });
    @endphp

    <script>
document.addEventListener('DOMContentLoaded', function () {

    // ===========================
    // Auto-remplissage adresse client
    // ===========================
    const clients = @json($clients_map);
    const clientSelect = document.getElementById('client-select');

    if (clientSelect) {

        function fillAddress(id) {

            const data = clients[id] || {};

            document.getElementById('rue').value = data.rue || '';
            document.getElementById('quartier').value = data.quartier || '';
            document.getElementById('porte').value = data.porte || '';
            document.getElementById('repere').value = data.repere || '';

        }

        clientSelect.addEventListener('change', function () {
            fillAddress(this.value);
        });

        if (clientSelect.value) {
            fillAddress(clientSelect.value);
        }

    }


    // ===========================
    // Gestion fréquence
    // ===========================

    const frequence = document.getElementById('frequence');
    const hebdo = document.getElementById('jour_hebdo');
    const mensuel = document.getElementById('jour_mensuel');
    const hidden = document.getElementById('jour_collecte');

    function updateJourCollecte() {

        if (frequence.value === 'hebdomadaire') {

            hebdo.classList.remove('d-none');
            mensuel.classList.add('d-none');

            hidden.value = hebdo.value;

        }
        else if (frequence.value === 'mensuelle') {

            hebdo.classList.add('d-none');
            mensuel.classList.remove('d-none');

            hidden.value = mensuel.value;

        }
        else {

            hebdo.classList.remove('d-none');
            mensuel.classList.add('d-none');

            hidden.value = '';

        }

    }

    frequence.addEventListener('change', updateJourCollecte);

    hebdo.addEventListener('change', function () {
        hidden.value = this.value;
    });

    mensuel.addEventListener('change', function () {
        hidden.value = this.value;
    });

    updateJourCollecte();

});
</script>
@endif
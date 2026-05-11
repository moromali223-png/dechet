@extends('layouts.app')

@section('title', 'Créer un abonnement')

@section('content')
<div class="container ">
    <div class="">
        <div class="">
            <div class="c">
                <div class="">
                    <h4 class="mb-0">
                        <i class="bx bx-package me-2"></i>
                        Créer un abonnement
                    </h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6 class="alert-heading">
                                <i class="bx bx-error-circle me-1"></i>
                                Veuillez corriger les erreurs suivantes :
                            </h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('abonnements.store') }}" method="POST">
                        @csrf

                        <!-- Client -->
                        @if(auth()->user()->role === 'admin')
                            <div class="mb-4">
                                <label for="client_id" class="form-label">
                                    Client <span class="text-danger">*</span>
                                </label>
                                <select
                                    name="client_id"
                                    id="client_id"
                                    class="form-select @error('client_id') is-invalid @enderror"
                                    required
                                >
                                    <option value="">-- Sélectionner un client --</option>
                                    @foreach($clients as $client)
                                        <option
                                            value="{{ $client->id }}"
                                            {{ old('client_id') == $client->id ? 'selected' : '' }}
                                        >
                                            {{ $client->user->name ?? 'Client' }} - {{ $client->zone->nom ?? 'Aucune zone' }}
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
                            <input type="hidden" name="client_id" value="{{ auth()->id() }}">
                        @endif

                        <!-- Type d'abonnement -->
<div class="mb-3">
    <label for="type_abonnement" class="form-label">
        Type d'abonnement <span class="text-danger">*</span>
    </label>
    <select
        id="type_abonnement"
        name="type_abonnement"
        class="form-select @error('type_abonnement') is-invalid @enderror"
        required
    >
        <option value="">-- Sélectionner un type --</option>
        
        <option value="hebdomadaire" 
            {{ old('type_abonnement') == 'hebdomadaire' ? 'selected' : '' }}>
            Abonnement Hebdomadaire
        </option>
        
        <option value="mensuel" 
            {{ old('type_abonnement') == 'mensuel' ? 'selected' : '' }}>
            Abonnement Mensuel
        </option>
        
        <option value="annuel" 
            {{ old('type_abonnement') == 'annuel' ? 'selected' : '' }}>
            Abonnement Annuel
        </option>
        
        <option value="premium" 
            {{ old('type_abonnement') == 'premium' ? 'selected' : '' }}>
            Abonnement Premium
        </option>
    </select>
    
    @error('type_abonnement')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

                      <!-- Type de déchet -->
<div class="mb-3">
    <label for="type_dechet" class="form-label">
        Type de déchet <span class="text-danger">*</span>
    </label>
    <select
        id="type_dechet"
        name="type_dechet"
        class="form-select @error('type_dechet') is-invalid @enderror"
        required
    >
        <option value="">-- Sélectionner le type de déchet --</option>
        
        <option value="plastique" 
            {{ old('type_dechet') == 'plastique' ? 'selected' : '' }}>
            Plastique
        </option>
        
        <option value="papier" 
            {{ old('type_dechet') == 'papier' ? 'selected' : '' }}>
            Papier / Carton
        </option>
        
        <option value="metal" 
            {{ old('type_dechet') == 'metal' ? 'selected' : '' }}>
            Métal
        </option>
        
        <option value="verre" 
            {{ old('type_dechet') == 'verre' ? 'selected' : '' }}>
            Verre
        </option>
        
        <option value="organique" 
            {{ old('type_dechet') == 'organique' ? 'selected' : '' }}>
            Déchets Organiques (Cuisine)
        </option>
        
        <option value="electronique" 
            {{ old('type_dechet') == 'electronique' ? 'selected' : '' }}>
            Déchets Électroniques (E-waste)
        </option>
        
        <option value="autre" 
            {{ old('type_dechet') == 'autre' ? 'selected' : '' }}>
            Autre
        </option>
    </select>
    
    @error('type_dechet')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
                        <!-- Fréquence et jour -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="frequence" class="form-label">
                                    Fréquence <span class="text-danger">*</span>
                                </label>
                                <select
                                    id="frequence"
                                    name="frequence"
                                    class="form-select @error('frequence') is-invalid @enderror"
                                    required
                                >
                                    <option value="">-- Choisir --</option>
                                    <option value="hebdomadaire" {{ old('frequence') == 'hebdomadaire' ? 'selected' : '' }}>
                                        Hebdomadaire
                                    </option>
                                    <option value="mensuelle" {{ old('frequence') == 'mensuelle' ? 'selected' : '' }}>
                                        Mensuelle
                                    </option>
                                </select>
                                @error('frequence')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        
                               <div class="col-md-6 mb-3">
    <label for="jour_collecte" class="form-label">
        Jour de collecte <span class="text-danger">*</span>
    </label>

    <select
        id="jour_collecte"
        name="jour_collecte"
        class="form-select @error('jour_collecte') is-invalid @enderror"
        required
    >
        <option value="">-- Sélectionner un jour --</option>

        <option value="lundi" {{ old('jour_collecte') == 'lundi' ? 'selected' : '' }}>
            Lundi
        </option>
        <option value="mardi" {{ old('jour_collecte') == 'mardi' ? 'selected' : '' }}>
            Mardi
        </option>
        <option value="mercredi" {{ old('jour_collecte') == 'mercredi' ? 'selected' : '' }}>
            Mercredi
        </option>
        <option value="jeudi" {{ old('jour_collecte') == 'jeudi' ? 'selected' : '' }}>
            Jeudi
        </option>
        <option value="vendredi" {{ old('jour_collecte') == 'vendredi' ? 'selected' : '' }}>
            Vendredi
        </option>
        <option value="samedi" {{ old('jour_collecte') == 'samedi' ? 'selected' : '' }}>
            Samedi
        </option>
        <option value="dimanche" {{ old('jour_collecte') == 'dimanche' ? 'selected' : '' }}>
            Dimanche
        </option>
    </select>

    @error('jour_collecte')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
                               
                        </div>

                        <!-- Poids et montant -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="poids_estime" class="form-label">
                                    Poids estimé (kg) <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="number"
                                    id="poids_estime"
                                    name="poids_estime"
                                    class="form-control @error('poids_estime') is-invalid @enderror"
                                    value="{{ old('poids_estime') }}"
                                    step="0.01"
                                    min="0"
                                    required
                                >
                                @error('poids_estime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="montant" class="form-label">Montant</label>
                                <input
                                    type="number"
                                    id="montant"
                                    name="montant"
                                    class="form-control @error('montant') is-invalid @enderror"
                                    value="{{ old('montant') }}"
                                    step="0.01"
                                    min="0"
                                >
                                @error('montant')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_debut" class="form-label">
                                    Date de début <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="date_debut"
                                    name="date_debut"
                                    class="form-control @error('date_debut') is-invalid @enderror"
                                    value="{{ old('date_debut') }}"
                                    required
                                >
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_fin" class="form-label">
                                    Date de fin <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="date_fin"
                                    name="date_fin"
                                    class="form-control @error('date_fin') is-invalid @enderror"
                                    value="{{ old('date_fin') }}"
                                    required
                                >
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Adresse de collecte -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="bx bx-map-pin me-2"></i>
                                Adresse de collecte
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="rue" class="form-label">Rue</label>
                                    <input
                                        type="text"
                                        id="rue"
                                        name="rue"
                                        class="form-control @error('rue') is-invalid @enderror"
                                        value="{{ old('rue') }}"
                                        placeholder="Ex: Avenue des Palmiers"
                                    >
                                    @error('rue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="quartier" class="form-label">Quartier</label>
                                    <input
                                        type="text"
                                        id="quartier"
                                        name="quartier"
                                        class="form-control @error('quartier') is-invalid @enderror"
                                        value="{{ old('quartier') }}"
                                        placeholder="Ex: Plateau"
                                    >
                                    @error('quartier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="porte" class="form-label">Porte</label>
                                    <input
                                        type="text"
                                        id="porte"
                                        name="porte"
                                        class="form-control @error('porte') is-invalid @enderror"
                                        value="{{ old('porte') }}"
                                        placeholder="Ex: Porte 12, Appartement 3B"
                                    >
                                    @error('porte')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="repere" class="form-label">Références / Point de repère</label>
                                    <textarea
                                        id="repere"
                                        name="repere"
                                        class="form-control @error('repere') is-invalid @enderror"
                                        rows="2"
                                        placeholder="Ex: Près de la pharmacie, derrière l'école..."
                                    >{{ old('repere') }}</textarea>
                                    @error('repere')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('abonnements.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>
                                Retour
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Enregistrer l'abonnement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const frequenceSelect = document.getElementById('frequence');
    const jourCollecteSelect = document.getElementById('jour_collecte');

    function updateJourCollecteOptions() {
        const frequence = frequenceSelect.value;
        const currentValue = jourCollecteSelect.value;

        // Clear existing options
        jourCollecteSelect.innerHTML = '<option value="">-- Sélectionner un jour --</option>';

        if (frequence === 'hebdomadaire') {
            const jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
            jours.forEach(jour => {
                const option = document.createElement('option');
                option.value = jour;
                option.textContent = jour.charAt(0).toUpperCase() + jour.slice(1);
                if (jour === currentValue) option.selected = true;
                jourCollecteSelect.appendChild(option);
            });
        } else if (frequence === 'mensuelle') {
            for (let i = 1; i <= 28; i++) {
                const option = document.createElement('option');
                option.value = i.toString();
                option.textContent = i + (i === 1 ? 'er' : 'ème') + ' jour du mois';
                if (i.toString() === currentValue) option.selected = true;
                jourCollecteSelect.appendChild(option);
            }
        }
    }

    frequenceSelect.addEventListener('change', updateJourCollecteOptions);
    updateJourCollecteOptions(); // Initialize on page load
});
</script>
@endsection
@extends('layouts.app')

@section('title', 'Modifier un abonnement')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Modifier l'abonnement</h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6 class="alert-heading mb-2">
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

                    <form action="{{ route('abonnements.update', $abonnement->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Type d'abonnement -->
                        <div class="mb-3">
                            <label for="type_abonnement" class="form-label">
                                Type d'abonnement <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="type_abonnement"
                                name="type_abonnement"
                                class="form-control @error('type_abonnement') is-invalid @enderror"
                                value="{{ old('type_abonnement', $abonnement->type_abonnement) }}"
                                required
                            >
                            @error('type_abonnement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type de déchet -->
                        <div class="mb-3">
                            <label for="type_dechet" class="form-label">
                                Type de déchet <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="type_dechet"
                                name="type_dechet"
                                class="form-control @error('type_dechet') is-invalid @enderror"
                                value="{{ old('type_dechet', $abonnement->type_dechet) }}"
                                required
                            >
                            @error('type_dechet')
                                <div class="invalid-feedback">{{ $message }}</div>
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
                                    <option value="hebdomadaire"
                                        {{ old('frequence', $abonnement->frequence) == 'hebdomadaire' ? 'selected' : '' }}>
                                        Hebdomadaire
                                    </option>
                                    <option value="mensuelle"
                                        {{ old('frequence', $abonnement->frequence) == 'mensuelle' ? 'selected' : '' }}>
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
                                <input
                                    type="text"
                                    id="jour_collecte"
                                    name="jour_collecte"
                                    class="form-control @error('jour_collecte') is-invalid @enderror"
                                    value="{{ old('jour_collecte', $abonnement->jour_collecte) }}"
                                    required
                                >
                                @error('jour_collecte')
                                    <div class="invalid-feedback">{{ $message }}</div>
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
                                    value="{{ old('poids_estime', $abonnement->poids_estime) }}"
                                    step="0.01"
                                    min="0"
                                    required
                                >
                                @error('poids_estime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="montant" class="form-label">
                                    Montant
                                </label>
                                <input
                                    type="number"
                                    id="montant"
                                    name="montant"
                                    class="form-control @error('montant') is-invalid @enderror"
                                    value="{{ old('montant', $abonnement->montant) }}"
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
                                    value="{{ old('date_debut', optional($abonnement->date_debut)->format('Y-m-d')) }}"
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
                                    value="{{ old('date_fin', optional($abonnement->date_fin)->format('Y-m-d')) }}"
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
                                        value="{{ old('rue', $abonnement->rue) }}"
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
                                        value="{{ old('quartier', $abonnement->quartier) }}"
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
                                        value="{{ old('porte', $abonnement->porte) }}"
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
                                    >{{ old('repere', $abonnement->repere) }}</textarea>
                                    @error('repere')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('abonnements.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>
                                Retour
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Mettre à jour
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
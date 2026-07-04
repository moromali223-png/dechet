@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Éditer la Planification</h1>
    <form action="{{ route('planifications.update', $planification) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="code_planification">Code Planification</label>
            <input type="text" class="form-control" id="code_planification" name="code_planification" value="{{ $planification->code_planification }}" required>
        </div>
        <div class="form-group">
            <label for="nom_tournee">Nom Tournée</label>
            <input type="text" class="form-control" id="nom_tournee" name="nom_tournee" value="{{ $planification->nom_tournee }}">
        </div>


        <div class="form-group">
            <label for="jour_semaine">Jour de la semaine</label>
            <select class="form-control" id="jour_semaine" name="jour_semaine" required>
                <option value="">-- Sélectionnez un jour --</option>
                <option value="lundi" {{ $planification->jour_semaine == 'lundi' ? 'selected' : '' }}>Lundi</option>
                <option value="mardi" {{ $planification->jour_semaine == 'mardi' ? 'selected' : '' }}>Mardi</option>
                <option value="mercredi" {{ $planification->jour_semaine == 'mercredi' ? 'selected' : '' }}>Mercredi</option>
                <option value="jeudi" {{ $planification->jour_semaine == 'jeudi' ? 'selected' : '' }}>Jeudi</option>
                <option value="vendredi" {{ $planification->jour_semaine == 'vendredi' ? 'selected' : '' }}>Vendredi</option>
                <option value="samedi" {{ $planification->jour_semaine == 'samedi' ? 'selected' : '' }}>Samedi</option>
                <option value="dimanche" {{ $planification->jour_semaine == 'dimanche' ? 'selected' : '' }}>Dimanche</option>
            </select>
        </div>
        <div class="form-group">
            <label for="date_prevue">Date Prévue</label>
            <input type="date" class="form-control" id="date_prevue" name="date_prevue" value="{{ $planification->date_prevue }}">
        </div>
        <div class="form-group">
            <label for="periode">Période</label>
            <select class="form-control" id="periode" name="periode" required>
                <option value="HEBDOMADAIRE" {{ $planification->periode == 'HEBDOMADAIRE' ? 'selected' : '' }}>Hebdomadaire</option>
                <option value="BI_HEBDOMADAIRE" {{ $planification->periode == 'BI_HEBDOMADAIRE' ? 'selected' : '' }}>Bi-hebdomadaire</option>
            </select>
        </div>
        <div class="form-group">
            <label for="type_collecte">Type Collecte</label>
            <input type="text" class="form-control" id="type_collecte" name="type_collecte" value="{{ $planification->type_collecte }}" required>
        </div>
        <div class="form-group">
            <label for="statut">Statut</label>
            <select class="form-control" id="statut" name="statut" required>
                <option value="planifiee" {{ $planification->statut == 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                <option value="assignee" {{ $planification->statut == 'assignee' ? 'selected' : '' }}>Affectée</option>
                <option value="en_route" {{ $planification->statut == 'en_route' ? 'selected' : '' }}>En route</option>
                <option value="en_cours" {{ $planification->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="terminee" {{ $planification->statut == 'terminee' ? 'selected' : '' }}>Terminée</option>
                <option value="annulee" {{ $planification->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
                <option value="reportee" {{ $planification->statut == 'reportee' ? 'selected' : '' }}>Reportée</option>
            </select>
        </div>
        <div class="form-group">
            <label for="agent_id">Agent</label>
            <select class="form-control" id="agent_id" name="agent_id">
                <option value="">-- Sélectionnez un agent --</option>
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ $planification->agent_id == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="abonnement_id">Abonnement</label>
            <select class="form-control" id="abonnement_id" name="abonnement_id">
                <option value="">Aucun</option>
                @foreach($abonnements as $abonnement)
                    <option value="{{ $abonnement->id }}" {{ $planification->abonnement_id == $abonnement->id ? 'selected' : '' }}>
                        Abonnement #{{ $abonnement->id }} - {{ $abonnement->type_abonnement }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="zone_id">Zone</label>
            <select class="form-control" id="zone_id" name="zone_id" required>
                @foreach($zones as $zone)
                <option value="{{ $zone->id }}" {{ $planification->zone_id == $zone->id ? 'selected' : '' }}>
                    {{ $zone->nom ?? $zone->nom_zone ?? 'Zone #' . $zone->id }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="collecteur_id">Collecteur</label>
            <select class="form-control" id="collecteur_id" name="collecteur_id" required>
                @foreach($collecteurs as $collecteur)
                <option value="{{ $collecteur->id }}" {{ $planification->collecteur_id == $collecteur->id ? 'selected' : '' }}>
                    {{ $collecteur->name ?? $collecteur->nom_collecteur ?? 'Collecteur #' . $collecteur->id }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="declaration_id">Déclaration</label>
            <select class="form-control" id="declaration_id" name="declaration_id">
                <option value="">Aucune</option>
                @foreach($declarations as $declaration)
                <option value="{{ $declaration->id }}" {{ $planification->declaration_id == $declaration->id ? 'selected' : '' }}>{{ $declaration->id }}</option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ordre_passage">Ordre de passage</label>
                    <input type="number" class="form-control" id="ordre_passage" name="ordre_passage" min="1" value="{{ $planification->ordre_passage }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="duree_estimee">Durée estimée (min)</label>
                    <input type="number" class="form-control" id="duree_estimee" name="duree_estimee" min="15" value="{{ $planification->duree_estimee }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="priorite">Priorité</label>
                    <input type="number" class="form-control" id="priorite" name="priorite" min="1" max="5" value="{{ $planification->priorite ?? 1 }}">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
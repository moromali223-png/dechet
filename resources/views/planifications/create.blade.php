@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Créer une Planification</h1>
    <form action="{{ route('planifications.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="code_planification">Code Planification</label>
            <input type="text" class="form-control" id="code_planification" name="code_planification" required>
        </div>
        <div class="form-group">
            <label for="nom_tournee">Nom Tournée</label>
            <input type="text" class="form-control" id="nom_tournee" name="nom_tournee">
        </div>
        <div class="form-group">
            <label for="jour_semaine">Jour Semaine</label>
            <input type="text" class="form-control" id="jour_semaine" name="jour_semaine" required>
        </div>
        <div class="form-group">
            <label for="date_prevue">Date Prévue</label>
            <input type="date" class="form-control" id="date_prevue" name="date_prevue">
        </div>
        <div class="form-group">
            <label for="periode">Période</label>
            <select class="form-control" id="periode" name="periode" required>
                <option value="HEBDOMADAIRE">Hebdomadaire</option>
                <option value="BI_HEBDOMADAIRE">Bi-hebdomadaire</option>
            </select>
        </div>
        <div class="form-group">
            <label for="type_collecte">Type Collecte</label>
            <input type="text" class="form-control" id="type_collecte" name="type_collecte" required>
        </div>
        <div class="form-group">
            <label for="statut">Statut</label>
            <select class="form-control" id="statut" name="statut" required>
                <option value="planifiee">Planifiée</option>
                <option value="assignee">Affectée</option>
                <option value="en_route">En route</option>
                <option value="en_cours">En cours</option>
                <option value="terminee">Terminée</option>
                <option value="annulee">Annulée</option>
                <option value="reportee">Reportée</option>
            </select>
        </div>
        <div class="form-group">
    <label for="zone_id">Zone</label>
    <select class="form-control" id="zone_id" name="zone_id" required>
        <option value="">-- Sélectionnez une zone --</option>
        @forelse($zones ?? [] as $zone)
            <option value="{{ $zone->id }}">{{ $zone->nom_zone ?? $zone->nom ?? 'Zone sans nom' }}</option>
        @empty
            <option value="">Aucune zone disponible</option>
        @endforelse
    </select>
</div>

<div class="form-group">
    <label for="agent_id">Agent</label>
    <select class="form-control" id="agent_id" name="agent_id">
        <option value="">-- Sélectionnez un agent --</option>
        @foreach($agents as $agent)
            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="collecteur_id">Collecteur</label>
    <select class="form-control" id="collecteur_id" name="collecteur_id">
        <option value="">-- Sélectionnez un collecteur --</option>
        @forelse($collecteurs ?? [] as $collecteur)
            <option value="{{ $collecteur->id }}">
                {{ $collecteur->nom_collecteur ?? optional($collecteur->user)->name ?? 'Collecteur #' . $collecteur->id }}
            </option>
        @empty
            <option value="">Aucun collecteur disponible</option>
        @endforelse
    </select>
</div>
        <div class="form-group">
            <label for="abonnement_id">Abonnement</label>
            <select class="form-control" id="abonnement_id" name="abonnement_id">
                <option value="">Aucun</option>
                @foreach($abonnements as $abonnement)
                    <option value="{{ $abonnement->id }}">Abonnement #{{ $abonnement->id }} - {{ $abonnement->type_abonnement }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="declaration_id">Déclaration</label>
            <select class="form-control" id="declaration_id" name="declaration_id">
                <option value="">Aucune</option>
                @foreach($declarations as $declaration)
                <option value="{{ $declaration->id }}">{{ $declaration->id }}</option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="ordre_passage">Ordre de passage</label>
                    <input type="number" class="form-control" id="ordre_passage" name="ordre_passage" min="1" value="1">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="duree_estimee">Durée estimée (min)</label>
                    <input type="number" class="form-control" id="duree_estimee" name="duree_estimee" min="15" value="60">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="priorite">Priorité</label>
                    <input type="number" class="form-control" id="priorite" name="priorite" min="1" max="5" value="1">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
@endsection
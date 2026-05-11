@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
             <h1>Planifications</h1>
             <p class="text-muted mb-0">Gestion des collecteurs enregistrés dans le système.</p>
        </div>
       
           <a href="{{ route('planifications.create') }}" class="btn btn-primary">Créer une planification</a>
    </div>
    
  
    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Nom Tournée</th>
                <th>Jour Semaine</th>
                <th>Date Prévue</th>
                <th>Période</th>
                <th>Type Collecte</th>
                <th>Statut</th>
                <th>Zone</th>
                <th>Collecteur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($planifications as $planification)
            <tr>
                <td>{{ $planification->code_planification }}</td>
                <td>{{ $planification->nom_tournee }}</td>
                <td>{{ $planification->jour_semaine }}</td>
                <td>{{ $planification->date_prevue }}</td>
                <td>{{ $planification->periode }}</td>
                <td>{{ $planification->type_collecte }}</td>
                <td>{{ $planification->statut }}</td>
                <td>{{ $planification->zone->nom ?? $planification->zone->nom_zone ?? 'N/A' }}</td>
                <td>{{ $planification->collecteur->user->name ?? $planification->collecteur->nom_collecteur ?? 'N/A' }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2 flex-nowrap">
                         <a href="{{ route('planifications.show', $planification) }}" class="btn btn-sm btn-info me-2" title="Voir">
                        <i class="bx bx-show"></i>
                    </a>
                    <a href="{{ route('planifications.edit', $planification) }}" class="btn btn-sm btn-warning me-2" title="Modifier">
                        <i class="bx bx-edit"></i>
                    </a>
                    <form action="{{ route('planifications.destroy', $planification) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                            <i class="bx bx-trash"></i>
                        </button>
                    </form>
                    </div>
                   
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $planifications->links() }}
</div>
@endsection
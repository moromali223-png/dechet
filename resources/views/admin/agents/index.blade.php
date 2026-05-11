@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
     <div>
       <h1 class="mb-1">Liste des Agents</h1>
       <p class="text-muted mb-0">Gestion des Agents enregistrés dans le système.</p>
     </div>
    
    <a href="{{ route('agents.create') }}" class="btn btn-primary mb-3">Ajouter un Agent</a>

    </div>
    
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Matricule</th>
                <th>Qualification</th>
                <th>Actions</th>
            </tr>
            
            
        </thead>
        <tbody>
            @forelse($agents as $agent)
                <tr>
                    <td>{{ $agent->user->name }}</td>
                    <td>{{ $agent->user->email }}</td>
                    <td>{{ $agent->user->telephone }}</td>
                    <td>{{ $agent->user->address }}</td>
                    <td>{{ $agent->matricul }}</td>
                    <td>{{ $agent->qualification }}</td>
                   <td>
    <div class="d-flex align-items-center gap-2 flex-nowrap">

        {{-- Voir --}}
        <a href="{{ route('agents.show', $agent->id) }}"
           class="btn btn-sm btn-info"
           title="Voir">
            <i class="bx bx-show"></i>
        </a>

        {{-- Modifier --}}
        <a href="{{ route('agents.edit', $agent->id) }}"
           class="btn btn-sm btn-warning"
           title="Modifier">
            <i class="bx bx-edit"></i>
        </a>

        {{-- Supprimer --}}
        <form action="{{ route('agents.destroy', $agent->id) }}"
              method="POST" class="m-0">
            @csrf
            @method('DELETE')

            <button type="submit"
                class="btn btn-sm btn-danger"
                title="Supprimer"
                onclick="return confirm('Êtes-vous sûr ?')">
                <i class="bx bx-trash"></i>
            </button>
        </form>

    </div>
</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Aucun agent trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
  </div>

   
@endsection
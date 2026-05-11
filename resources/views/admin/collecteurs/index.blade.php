@extends('layouts.app')

@section('title', 'Liste des Collecteurs')

@section('content')
<div class="container">
     <!-- #region -->
     <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
        <h1 class="mb-1">Liste des Collecteurs</h1>
        <p class="text-muted mb-0">Gestion des collecteurs enregistrés dans le système.</p>
    </div>
     <a href="{{ route('collecteurs.create') }}" class="btn btn-primary mb-3">Ajouter un Collecteur</a>
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
                <th>Num permis</th>
                <th>Matricule</th>
                <th>Adresse</th>
                <th>Zone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($collecteurs as $collecteur)
            <tr>
                <td>{{ $collecteur->user->name }}</td>
                <td>{{ $collecteur->user->email }}</td>
                <td>{{ $collecteur->user->telephone }}</td>
                <td>{{ $collecteur->numpermis }}</td>
                <td>{{ $collecteur->matricul }}</td>
                    <td>{{ $collecteur->user->address }}</td>
                <td>{{ $collecteur->zone->nom ?? 'Non défini' }}</td>
                <td>
                    
    <div class="d-flex align-items-center gap-2 flex-nowrap">

        {{-- Voir --}}
        <a href="{{ route('collecteurs.show', $collecteur->id) }}"
           class="btn btn-sm btn-info"
           title="Voir">
            <i class="bx bx-show"></i>
        </a>

        {{-- Modifier --}}
        <a href="{{ route('collecteurs.edit', $collecteur->id) }}"
           class="btn btn-sm btn-warning"
           title="Modifier">
            <i class="bx bx-edit"></i>
        </a>

        {{-- Supprimer --}}
        <form action="{{ route('collecteurs.destroy', $collecteur->id) }}"
              method="POST" class="m-0">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="btn btn-sm btn-danger"
                    title="Supprimer"
                    onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                <i class="bx bx-trash"></i>
            </button>
        </form>

    </div>
</td>
                
            </tr>
            @empty
            <tr><td colspan="7" class="text-center">Aucun collecteur trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $collecteurs->links() }} <!-- Pagination -->
</div>
@endsection
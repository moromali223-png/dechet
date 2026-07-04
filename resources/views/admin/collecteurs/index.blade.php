@extends('layouts.app')

@section('title', 'Liste des Collecteurs')

@section('content')
<div class="container-fluid px-2">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Liste des Collecteurs</h1>
            <p class="text-muted mb-0">
                Gestion des collecteurs enregistrés dans le système.
            </p>
        </div>
        <a href="{{ route('collecteurs.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i>
            Ajouter un Collecteur
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            <i class="bx bx-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg rounded-2">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                             <th>Téléphone</th> 
                            
                            <th>Adresse</th>
                            <th>Zone</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
<tbody>
@forelse($collecteurs as $collecteur)
    <tr>
        <td><strong>{{ $collecteur->name }}</strong></td>
        <td>{{ $collecteur->email }}</td>
        <td>{{ $collecteur->telephone }}</td>
        <td>{{ $collecteur->address ?? 'Non renseignée' }}</td>

        {{-- ZONE CORRIGÉE --}}
        <td>
            {{ $collecteur->zone->nom ?? 'Non défini' }}
        </td>

        <td class="text-center">
            <div class="d-flex justify-content-center gap-2">

                <a href="{{ route('collecteurs.show', $collecteur->id) }}"
                   class="btn btn-info btn-sm rounded-pill">
                    <i class="bx bx-show"></i>
                </a>

                <a href="{{ route('collecteurs.edit', $collecteur->id) }}"
                   class="btn btn-warning btn-sm rounded-pill">
                    <i class="bx bx-edit"></i>
                </a>

                <form action="{{ route('collecteurs.destroy', $collecteur->id) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-sm rounded-pill"
                            onclick="return confirm('Supprimer ?')">
                        <i class="bx bx-trash"></i>
                    </button>
                </form>

            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-4">
            Aucun collecteur trouvé
        </td>
    </tr>
@endforelse
</tbody>
                </table>
            </div>

            @if($collecteurs->hasPages())
                <div class="p-4">
                    {{ $collecteurs->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
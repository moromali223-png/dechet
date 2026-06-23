@extends('layouts.app')

@section('title', 'Liste des Zones')

@section('content')
<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Liste des Zones</h1>
            <p class="text-muted mb-0">
                Gestion des zones géographiques du système.
            </p>
        </div>
        <a href="{{ route('zones.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i>
            Ajouter une zone
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            <i class="bx bx-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">
            <i class="bx bx-error-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg rounded-2">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Ville</th>
                            <th>Description</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($zones as $zone)
                            <tr>
                                <td><strong>{{ $zone->id }}</strong></td>
                                <td>{{ $zone->nom }}</td>
                                <td>{{ $zone->ville }}</td>
                                <td>{{ $zone->description ?? 'Aucune description' }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2 flex-nowrap">
                                        <!-- Voir -->
                                        <a href="{{ route('zones.show', $zone->id) }}"
                                           class="btn btn-sm btn-info rounded-pill"
                                           title="Voir">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        <!-- Modifier -->
                                        <a href="{{ route('zones.edit', $zone->id) }}"
                                           class="btn btn-sm btn-warning rounded-pill"
                                           title="Modifier">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <!-- Supprimer -->
                                        <form action="{{ route('zones.destroy', $zone->id) }}"
                                              method="POST"
                                              class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger rounded-pill"
                                                    title="Supprimer"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette zone ?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bx bx-map-x display-4 text-muted"></i>
                                    <p class="mt-3 text-muted">Aucune zone trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($zones->hasPages())
                <div class="p-4">
                    {{ $zones->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
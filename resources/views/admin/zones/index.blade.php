@extends('layouts.app')

@section('content')

<h2>Liste des zones</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<a href="{{ route('zones.create') }}" class="btn btn-primary">Ajouter une zone</a>

<table class="table table-bordered mt-4">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Ville</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($zones as $zone)
        <tr>
            <td>{{ $zone->id }}</td>
            <td>{{ $zone->nom }}</td>
            <td>{{ $zone->ville }}</td>
            <td>{{ $zone->description ?? 'N/A' }}</td>
            <td>
                <a href="{{ route('zones.show', $zone->id) }}" class="btn btn-sm btn-info me-2" title="Voir">
                    <i class="bx bx-show"></i>
                </a>
                <a href="{{ route('zones.edit', $zone->id) }}" class="btn btn-sm btn-warning me-2" title="Modifier">
                    <i class="bx bx-edit"></i>
                </a>
                <form action="{{ route('zones.destroy', $zone->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette zone ?')">
                        <i class="bx bx-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Aucune zone trouvée.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $zones->links() }}

@endsection
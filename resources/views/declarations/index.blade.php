@extends('layouts.app')

@section('title', 'Déclarations')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Mes déclarations</h1>
            <p class="text-muted mb-0">Suivez vos déclarations de déchets manuelles et automatiques.</p>
        </div>
        <a href="{{ route('declarations.create') }}" class="btn btn-primary">Nouvelle déclaration</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Adresse</th>
                    <th>Abonnement</th>
                    <th>Type de déchet</th>
                    <th>Poids estimé</th>
                    <th>Statut</th>
                    <th>Créée le</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($declarations as $declaration)
                    <tr>
                        <td>{{ $declaration->id }}</td>
                        <td>{{ $declaration->user?->name ?? 'N/A' }}</td>
                        <td>{{ $declaration->user?->address ?? 'N/A' }}</td>
                        <td>{{ $declaration->abonnement?->type_abonnement ?? 'Manuel' }}</td>
                        <td>{{ $declaration->type_dechet }}</td>
                        <td>{{ $declaration->poids_estime ? number_format($declaration->poids_estime, 2, ',', ' ') . ' kg' : 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $declaration->statut === 'en_attente' ? 'bg-warning text-dark' : ($declaration->statut === 'planifiee' ? 'bg-info text-dark' : ($declaration->statut === 'validée' ? 'bg-success' : 'bg-secondary')) }}">
                            <span class="badge {{ $declaration->statut === 'en_attente' ? 'bg-warning text-dark' : ($declaration->statut === 'planifiee' ? 'bg-info text-dark' : ($declaration->statut === 'valide' ? 'bg-success' : 'bg-secondary')) }}">
                                {{ ucfirst(str_replace('_', ' ', $declaration->statut)) }}
                            </span>
                        </td>
                        <td>{{ $declaration->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('declarations.show', $declaration) }}" class="btn btn-sm btn-outline-primary me-2">Voir</a>
                            @if($declaration->statut === 'en_attente')
                                <a href="{{ route('declarations.edit', $declaration) }}" class="btn btn-sm btn-warning me-2">Modifier</a>
                                <form action="{{ route('declarations.destroy', $declaration) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression de cette déclaration ?');">Supprimer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Aucune déclaration trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $declarations->links() }}
    </div>
</div>
@endsection

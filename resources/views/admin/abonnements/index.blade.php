@extends('layouts.app')

@section('title', 'Abonnements')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Abonnements</h1>
            <p class="text-muted mb-0">Gérez les abonnements actifs et suivez leurs durées.</p>
        </div>
        <a href="{{ route('abonnements.create') }}" class="btn btn-primary">Nouvel abonnement</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Déchet</th>
                    <th>Fréquence</th>
                    <th>Adresse</th>
                    <th>Période</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($abonnements as $abonnement)
                    <tr>
                        <td>{{ $abonnement->id }}</td>
                        <td>
                            {{ $abonnement->type_abonnement }}
                            @if($abonnement->client?->user)
                                <div class="text-muted small mt-1">
                                    {{ $abonnement->client->user->name }}
                                    @if($abonnement->client->zone)
                                        - {{ $abonnement->client->zone->nom }}
                                    @endif
                                </div>
                            @elseif($abonnement->user)
                                <div class="text-muted small mt-1">{{ $abonnement->user->name }}</div>
                            @endif
                        </td>
                        <td>{{ $abonnement->type_dechet }}</td>
                        <td>{{ ucfirst($abonnement->frequence) }}</td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px;" title="{{ $abonnement->adresse_complete }}">
                                {{ $abonnement->adresse_complete }}
                            </div>
                            @if($abonnement->repere)
                                <small class="text-muted d-block" title="{{ $abonnement->repere }}">
                                    <i class="bx bx-info-circle"></i> {{ Str::limit($abonnement->repere, 30) }}
                                </small>
                            @endif
                        </td>
                        <td>{{ $abonnement->date_debut->format('d/m/Y') }} - {{ $abonnement->date_fin->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge {{ $abonnement->statut === 'actif' ? 'bg-success' : ($abonnement->statut === 'expiré' ? 'bg-secondary' : ($abonnement->statut === 'annulé' ? 'bg-danger' : 'bg-warning text-dark')) }}">
                                {{ ucfirst(str_replace('_', ' ', $abonnement->statut)) }}
                            </span>
                        </td>
                       <td class="text-end">
    <div class="d-inline-flex align-items-center gap-2 flex-nowrap">
        <!-- Voir -->
        <a href="{{ route('abonnements.show', $abonnement->id) }}"
           class="btn btn-sm btn-info"
           title="Voir">
            <i class="bx bx-show"></i>
        </a>

        <!-- Modifier -->
        <a href="{{ route('abonnements.edit', $abonnement->id) }}"
           class="btn btn-sm btn-warning"
           title="Modifier">
            <i class="bx bx-edit"></i>
        </a>

       
        <!-- Supprimer -->
        <form action="{{ route('abonnements.destroy', $abonnement->id) }}"
              method="POST"
              class="d-inline m-0">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn btn-sm btn-danger"
                    title="Supprimer"
                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet abonnement ?')">
                <i class="bx bx-trash"></i>
            </button>
        </form>
    </div>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Aucun abonnement trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $abonnements->links() }}
    </div>
</div>

<!-- Modals for rejection -->
@foreach($abonnements as $abonnement)
    @if(auth()->user()->role === 'admin' && $abonnement->statut === 'en_attente')
        <div class="modal fade" id="rejectModal{{ $abonnement->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $abonnement->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel{{ $abonnement->id }}">Rejeter l'abonnement #{{ $abonnement->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('abonnements.rejeter', $abonnement->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="motif_rejet{{ $abonnement->id }}" class="form-label">Motif du rejet</label>
                                <textarea class="form-control" id="motif_rejet{{ $abonnement->id }}" name="motif_rejet" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger">Rejeter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection

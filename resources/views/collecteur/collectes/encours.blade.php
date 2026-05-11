@extends('collecteur.layouts.app')

@section('title', 'Collectes en cours')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bx bx-play-circle text-primary me-2"></i>
                    Collectes en cours
                </h2>
                <p class="text-muted mb-0">Toutes les collectes en route ou en cours pour votre tournée.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge bg-info fs-6 px-4 py-3">{{ $tournees->count() }} mission(s)</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Zone</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Temps</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tournees as $tournee)
                            <tr>
                                <td>{{ $tournee->ordre_passage ?? $tournee->id }}</td>
                                <td>{{ optional($tournee->declaration?->user)->name ?? optional($tournee->abonnement?->client?->user)->name ?? 'Client inconnu' }}</td>
                                <td>{{ optional($tournee->zone)->nom ?? 'N/A' }}</td>
                                <td><span class="badge bg-label-primary">{{ ucfirst($tournee->type_collecte) }}</span></td>
                                <td>
                                    @php
                                        $statusColors = ['en_route' => 'warning', 'en_cours' => 'primary'];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$tournee->statut] ?? 'secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $tournee->statut)) }}
                                    </span>
                                </td>
                                <td>
                                    <div>{{ $tournee->heure_depart ? $tournee->heure_depart->format('H:i') : '-' }}</div>
                                    <div>{{ $tournee->heure_arrivee ? $tournee->heure_arrivee->format('H:i') : '-' }}</div>
                                </td>
                                <td class="text-center">
                                    @if($tournee->statut === 'en_route')
                                        <form method="POST" action="{{ route('collecteur.arrive', $tournee) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">Arrivée</button>
                                        </form>
                                    @elseif($tournee->statut === 'en_cours')
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#finishModal{{ $tournee->id }}">
                                            Collecter
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            @if($tournee->statut === 'en_cours')
                                <div class="modal fade" id="finishModal{{ $tournee->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('collecteur.finish', $tournee) }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmer la collecte</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Photo (optionnel)</label>
                                                        <input type="file" name="photo" class="form-control" accept="image/*">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-success">Enregistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-loader-circle display-2"></i>
                                        <div class="mt-3">Aucune collecte active en ce moment.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

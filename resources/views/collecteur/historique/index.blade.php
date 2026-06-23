@extends('collecteur.layouts.app')

@section('title', 'Historique des collectes')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bx bx-history text-secondary me-2"></i>
                    Historique
                </h2>
                <p class="text-muted mb-0">Toutes les collectes liées à votre compte collecteur.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge bg-secondary fs-6 px-4 py-3">{{ $collectes->count() }} enregistrements</span>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Collecte</th>
                            <th>Client</th>
                            <th>Zone</th>
                            <th>Date</th>
                            <th>Statut planification</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($collectes as $collecte)
                            @php
                                $plan = $collecte->planification;
                                $clientName =
                                    $plan?->declaration?->user?->name
                                    ?? $plan?->abonnement?->client?->user?->name
                                    ?? 'Client inconnu';
                            @endphp

                            <tr>
                                <td>{{ $collecte->id }}</td>

                                <td>{{ $plan?->code_planification ?? 'N/A' }}</td>

                                <td>{{ $clientName }}</td>

                                <td>{{ $plan?->zone?->nom ?? 'N/A' }}</td>

                                <td>{{ $collecte->created_at?->format('d/m/Y H:i') ?? '-' }}</td>

                                <td>
                                    <span class="badge bg-{{ $plan?->statut === 'terminee' ? 'success' : 'secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $plan?->statut ?? 'N/A')) }}
                                    </span>
                                </td>
                                <td>
    <a href="{{ route('collecteur.historique.show', $collecte->id) }}" 
       class="btn btn-sm btn-primary">
        <i class="bx bx-show"></i> Voir
    </a>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-history display-2"></i>
                                        <div class="mt-3">Aucun historique de collecte trouvé.</div>
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
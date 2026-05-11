@extends('collecteur.layouts.app')

@section('title', 'Collectes terminées')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bx bx-check-circle text-success me-2"></i>
                    Collectes terminées
                </h2>
                <p class="text-muted mb-0">Historique des collectes finalisées dans votre tournée.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge bg-success fs-6 px-4 py-3">{{ $collectes->count() }} collecte(s)</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            {{ session('success') }}
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
                            <th>Collecte</th>
                            <th>Client</th>
                            <th>Zone</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($collectes as $collecte)
                            <tr>
                                <td>{{ $collecte->id }}</td>
                                <td>{{ optional($collecte->planification)->code_planification ?? 'N/A' }}</td>
                                <td>{{ optional($collecte->planification?->declaration?->user)->name ?? optional($collecte->planification?->abonnement?->client?->user)->name ?? 'Client inconnu' }}</td>
                                <td>{{ optional(optional($collecte->planification)->zone)->nom ?? 'N/A' }}</td>
                                <td>{{ $collecte->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td><span class="badge bg-success">Terminée</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-check-circle display-2"></i>
                                        <div class="mt-3">Aucune collecte terminée trouvée.</div>
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

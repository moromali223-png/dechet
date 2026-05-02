@extends('layouts.app')

@section('title', 'Tournées du jour')

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h2 class="fw-bold mb-2">
                        <i class="bx bx-map-alt text-primary me-2"></i>
                        Tournées du jour
                    </h2>
                    <p class="text-muted mb-0">
                        Gérez et suivez les collectes planifiées en temps réel.
                    </p>
                </div>

                <div class="mt-3 mt-md-0">
                    <span class="badge bg-primary fs-6 px-4 py-3">
                        {{ now()->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Message succès -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            <i class="bx bx-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tableau -->
    <div class="card border-0 shadow-lg">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Client</th>
                            <th>Zone</th>
                            <th>Agent</th>
                            <th>Collecteur</th>
                            <th>Priorité</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tournees as $planification)
                            <tr>
                                <!-- Code -->
                                <td>
                                    <span class="fw-bold text-primary">
                                        {{ $planification->code_planification }}
                                    </span>
                                </td>

                                <!-- Client -->
                                <td>
                                    @php
                                        $clientName =
                                            optional(optional($planification->declaration)->user)->name
                                            ?? optional(optional($planification->abonnement)->client?->user)->name
                                            ?? 'Client inconnu';

                                        $isAbonnement = !is_null($planification->abonnement_id);
                                    @endphp

                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="avatar avatar-sm">
                                                <span class="avatar-initial rounded-circle bg-primary text-white">
                                                    {{ strtoupper(substr($clientName, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div>
                                            <h6 class="mb-1 fw-bold">
                                                {{ $clientName }}
                                            </h6>

                                            @if($isAbonnement)
                                                <span class="badge bg-success">
                                                    <i class="bx bx-refresh me-1"></i>
                                                    Abonnement
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bx bx-file me-1"></i>
                                                    Déclaration
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Zone -->
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ optional($planification->zone)->nom ?? 'N/A' }}
                                    </span>
                                </td>

                                <!-- Agent -->
                                <td>
                                    {{ optional($planification->agent)->name ?? 'Non assigné' }}
                                </td>

                                <!-- Collecteur -->
                                <td>
                                    {{ optional(optional($planification->collecteur)->user)->name ?? 'Non assigné' }}
                                </td>

                                <!-- Priorité -->
                                <td>
                                    @php
                                        $priorityColor = match(true) {
                                            $planification->priorite >= 4 => 'danger',
                                            $planification->priorite >= 2 => 'warning',
                                            default => 'success'
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $priorityColor }}">
                                        Priorité {{ $planification->priorite }}
                                    </span>
                                </td>

                                <!-- Statut -->
                                <td>
                                    @php
                                        $statusColors = [
                                            'planifiee' => 'secondary',
                                            'assignee' => 'info',
                                            'en_route' => 'warning',
                                            'en_cours' => 'primary',
                                            'terminee' => 'success',
                                        ];
                                    @endphp

                                    <span class="badge bg-{{ $statusColors[$planification->statut] ?? 'dark' }}">
                                        {{ ucfirst(str_replace('_', ' ', $planification->statut)) }}
                                    </span>
                                </td>

                                <!-- Action -->
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#statusModal{{ $planification->id }}">
                                        <i class="bx bx-edit-alt me-1"></i>
                                        Mettre à jour
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade"
                                 id="statusModal{{ $planification->id }}"
                                 tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">

                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="bx bx-refresh me-2"></i>
                                                Mise à jour de la tournée
                                            </h5>

                                            <button type="button"
                                                    class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                        </div>

                                        <form action="{{ route('planifications.status.update', $planification) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <div class="modal-body">

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        Code
                                                    </label>
                                                    <input type="text"
                                                           class="form-control"
                                                           value="{{ $planification->code_planification }}"
                                                           readonly>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">
                                                        Nouveau statut
                                                    </label>

                                                    <select name="statut"
                                                            class="form-select"
                                                            required>
                                                        <option value="planifiee">Planifiée</option>
                                                        <option value="assignee">Affectée</option>
                                                        <option value="en_route">En route</option>
                                                        <option value="en_cours">En cours</option>
                                                        <option value="terminee">Terminée</option>
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button"
                                                        class="btn btn-light"
                                                        data-bs-dismiss="modal">
                                                    Annuler
                                                </button>

                                                <button type="submit"
                                                        class="btn btn-primary">
                                                    <i class="bx bx-save me-1"></i>
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bx bx-calendar-x display-1 text-muted"></i>
                                    <h5 class="mt-3 text-muted">
                                        Aucune tournée prévue aujourd'hui
                                    </h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $tournees->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
@extends('layouts.app')

@section('title', 'Affectations')

@section('content')
<div class="container-fluid px-2">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Affectations des tournées</h1>
            <p class="text-muted mb-0">
                Gérez les affectations des agents et collecteurs.
            </p>
        </div>
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
                            <th>Code</th>
                            <th>Client</th>
                            <th>Source</th>
                            <!-- <th>Collecte</th> -->
                            <th>Date</th>
                            <th>Zone</th>
                            <th>Statut</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($planifications as $planification)
                            <tr>
                                <td>
                                    <strong>{{ $planification->code_planification }}</strong>
                                </td>

                                <td>
                                    {{
                                        optional(optional($planification->declaration)->user)->name
                                        ?? optional(optional($planification->abonnement)->client?->user)->name
                                        ?? 'N/A'
                                    }}
                                </td>

                                <td>
                                    @if($planification->declaration_id)
                                        <span class="badge bg-warning text-dark">
                                            Déclaration
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            Abonnement
                                        </span>
                                    @endif
                                </td>

                                <!-- <td>{{ ucfirst($planification->type_collecte) }}</td> -->

                                <td>
                                    {{ $planification->date_prevue?->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ $planification->zone->nom ?? 'N/A' }}
                                </td>

                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst($planification->statut) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <button
                                        class="btn btn-primary btn-sm rounded-pill px-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#assignModal{{ $planification->id }}">
                                        <i class="bx bx-user-check me-1"></i>
                                        Affecter
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Affectation -->
                            <div class="modal fade"
                                 id="assignModal{{ $planification->id }}"
                                 tabindex="-1"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">

                                        <div class="modal-header bg-primary text-white border-0">
                                            <h5 class="modal-title">
                                                <i class="bx bx-map-pin me-2"></i>
                                                Affecter la tournée
                                            </h5>
                                            <button type="button"
                                                    class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                        </div>
<form action="{{ route('affectations.assign', $planification) }}" 
      method="POST" 
      id="assignForm{{ $planification->id }}">

    @csrf

    <!-- ... tes champs ... -->

    <div class="modal-footer border-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            Annuler
        </button>

        <button type="submit" 
                form="assignForm{{ $planification->id }}" 
                class="btn btn-primary">
            <i class="bx bx-check-circle me-1"></i>
            Confirmer l'affectation
        </button>
    </div>
</form>

                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bx bx-calendar-x display-4 text-muted"></i>
                                    <p class="mt-3 text-muted">
                                        Aucune planification disponible.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $planifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
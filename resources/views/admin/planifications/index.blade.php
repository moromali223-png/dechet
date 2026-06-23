@extends('layouts.app')

@section('title', 'Planifications')

@section('content')
<div class="container-fluid px-2">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Planifications</h1>
            <p class="text-muted mb-0">
                Gestion des planifications des tournées de collecte.
            </p>
        </div>
        <a href="{{ route('planifications.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i>
            Créer une planification
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
                            <th>Code</th>
                            <th>Nom Tournée</th>
                            <th>Jour Semaine</th>
                            <!-- <th>Date Prévue</th> -->
                            <!-- <th>Période</th> -->
                            <!-- <th>Type Collecte</th> -->
                            <th>Statut</th>
                            <th>Zone</th>
                            <th>Collecteur</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($planifications as $planification)
                            <tr>
                                <td><strong>{{ $planification->code_planification }}</strong></td>
                                <td>{{ $planification->nom_tournee ?? 'N/A' }}</td>
                                <td>{{ $planification->jour_semaine }}</td>
                                <!-- <td>
                                    {{ $planification->date_prevue?->format('d/m/Y') ?? 'Non définie' }}
                                </td> -->
                                <!-- <td>{{ $planification->periode }}</td> -->
                                        <!-- <td>
                                            <span class="badge bg-primary">
                                                {{ ucfirst($planification->type_collecte) }}
                                            </span>
                                        </td> -->
                                <td>
                                    <span class="badge {{ $planification->statut === 'planifié' ? 'bg-info' : ($planification->statut === 'en_cours' ? 'bg-warning' : ($planification->statut === 'terminé' ? 'bg-success' : 'bg-secondary')) }}">
                                        {{ ucfirst($planification->statut) }}
                                    </span>
                                </td>
                                <td>{{ $planification->zone->nom ?? $planification->zone->nom_zone ?? 'N/A' }}</td>
                                <td>
                                    {{ $planification->collecteur?->user?->name ?? $planification->collecteur?->nom_collecteur ?? 'Non assigné' }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2 flex-nowrap">
                                        <!-- Voir -->
                                        <a href="{{ route('planifications.show', $planification) }}"
                                           class="btn btn-sm btn-info rounded-pill"
                                           title="Voir">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        <!-- Modifier -->
                                        <a href="{{ route('planifications.edit', $planification) }}"
                                           class="btn btn-sm btn-warning rounded-pill"
                                           title="Modifier">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <!-- Supprimer -->
                                        <form action="{{ route('planifications.destroy', $planification) }}"
                                              method="POST"
                                              class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger rounded-pill"
                                                    title="Supprimer"
                                                    onclick="return confirm('Voulez-vous vraiment supprimer cette planification ?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <i class="bx bx-calendar-x display-4 text-muted"></i>
                                    <p class="mt-3 text-muted">Aucune planification trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($planifications->hasPages())
                <div class="p-4">
                    {{ $planifications->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
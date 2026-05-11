@extends('agent.layouts.app')

@section('content')
<div class="row">
    <!-- Statistiques -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <div class="avatar-initial bg-label-primary rounded">
                            <i class="bx bx-package bx-sm"></i>
                        </div>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Total matières</span>
                <h3 class="card-title mb-2">{{ $stats['total_matieres'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <div class="avatar-initial bg-label-success rounded">
                            <i class="bx bx-check-circle bx-sm"></i>
                        </div>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Disponibles</span>
                <h3 class="card-title mb-2">{{ $stats['matieres_disponibles'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <div class="avatar-initial bg-label-warning rounded">
                            <i class="bx bx-trending-up bx-sm"></i>
                        </div>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Quantité totale</span>
                <h3 class="card-title mb-2">{{ number_format($stats['quantite_totale'], 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <div class="avatar-initial bg-label-danger rounded">
                            <i class="bx bx-error bx-sm"></i>
                        </div>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Épuisées</span>
                <h3 class="card-title mb-2">{{ $stats['matieres_epuisees'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Matières premières disponibles</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Type de déchet</th>
                                <th>Quantité totale</th>
                                <th>Quantité utilisée</th>
                                <th>Quantité restante</th>
                                <th>Unité</th>
                                <th>Disponibilité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($matieres as $matiere)
                            <tr>
                                <td><strong>{{ $matiere['type_dechet'] }}</strong></td>
                                <td>{{ number_format($matiere['quantite_totale'], 2) }}</td>
                                <td>{{ number_format($matiere['quantite_utilisee'], 2) }}</td>
                                <td><strong>{{ number_format($matiere['quantite_restante'], 2) }}</strong></td>
                                <td>{{ $matiere['unite'] }}</td>
                                <td>
                                    <span class="badge bg-label-{{ $matiere['disponibilite'] === 'disponible' ? 'success' : 'danger' }}">
                                        {{ ucfirst($matiere['disponibilite']) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('agent.matieres.show', $matiere['type_dechet']) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Aucune matière première disponible
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
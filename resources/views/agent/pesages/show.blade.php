@extends('agent.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Détails du pesage</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('agent.pesages.edit', $pesage) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bx bx-edit me-1"></i>
                        Modifier
                    </a>
                    <a href="{{ route('agent.pesages.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i>
                        Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Client</h6>
                        <p class="mb-3">{{ $pesage->collecte->planification->client->nom ?? 'N/A' }}</p>

                        <h6>Poids</h6>
                        <p class="mb-3"><strong>{{ $pesage->poids }} {{ $pesage->unite }}</strong></p>

                        <h6>Statut</h6>
                        <p class="mb-3">
                            <span class="badge bg-label-{{ $pesage->statut === 'termine' ? 'success' : 'warning' }}">
                                {{ ucfirst($pesage->statut) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Date de pesage</h6>
                        <p class="mb-3">{{ $pesage->created_at->format('d/m/Y H:i') }}</p>

                        <h6>Dernière modification</h6>
                        <p class="mb-3">{{ $pesage->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                @if($pesage->description)
                <div class="row">
                    <div class="col-12">
                        <h6>Description</h6>
                        <p class="mb-0">{{ $pesage->description }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Tris associés -->
        @if($pesage->tries->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Tris effectués sur ce pesage</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Type de déchet</th>
                                <th>Quantité triée</th>
                                <th>Qualité</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesage->tries as $tri)
                            <tr>
                                <td>{{ $tri->type_dechet }}</td>
                                <td>{{ $tri->quantite_trier }} {{ $tri->unite }}</td>
                                <td>{{ $tri->qualite ?? '-' }}</td>
                                <td>{{ $tri->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('agent.tries.show', $tri) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Informations collecte liée -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Collecte liée</h6>
            </div>
            <div class="card-body">
                <p><strong>Client:</strong> {{ $pesage->collecte->planification->client->nom ?? 'N/A' }}</p>
                <p><strong>Date:</strong> {{ $pesage->collecte->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Statut:</strong>
                    <span class="badge bg-label-{{ $this->getStatusColor($pesage->collecte->statut) }}">
                        {{ ucfirst(str_replace('_', ' ', $pesage->collecte->statut)) }}
                    </span>
                </p>
                <a href="{{ route('agent.collectes.show', $pesage->collecte) }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bx bx-show me-1"></i>
                    Voir la collecte
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@php
    function getStatusColor($status) {
        return match($status) {
            'en_cours' => 'warning',
            'terminee' => 'success',
            'arrive_au_centre' => 'info',
            'pesee' => 'primary',
            'triee' => 'secondary',
            default => 'secondary'
        };
    }
@endphp
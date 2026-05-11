@extends('agent.layouts.app')

@section('content')
<div class="row">
    <!-- Informations principales -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Détails de la collecte</h5>
                <a href="{{ route('agent.collectes.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i>
                    Retour
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Client</h6>
                        <p class="mb-3">{{ $collecte->planification->client->nom ?? 'N/A' }}</p>

                        <h6>Collecteur</h6>
                        <p class="mb-3">{{ $collecte->planification->collecteur->nom ?? 'N/A' }}</p>

                        <h6>Statut</h6>
                        <p class="mb-3">
                            <span class="badge bg-label-{{ $this->getStatusColor($collecte->statut) }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $collecte->statut)) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Date de création</h6>
                        <p class="mb-3">{{ $collecte->created_at->format('d/m/Y H:i') }}</p>

                        @if($collecte->heure_depart)
                        <h6>Heure de départ</h6>
                        <p class="mb-3">{{ $collecte->heure_depart->format('d/m/Y H:i') }}</p>
                        @endif

                        @if($collecte->heure_fin)
                        <h6>Heure d'arrivée</h6>
                        <p class="mb-3">{{ $collecte->heure_fin->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>

                @if($collecte->commentaire)
                <div class="row">
                    <div class="col-12">
                        <h6>Commentaire</h6>
                        <p class="mb-0">{{ $collecte->commentaire }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Photo -->
        @if($collecte->photo)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Photo de la collecte</h6>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $collecte->photo) }}" alt="Photo collecte" class="img-fluid rounded" style="max-height: 400px;">
            </div>
        </div>
        @endif

        <!-- Pesages associés -->
        @if($collecte->pesages->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Pesages effectués</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Poids</th>
                                <th>Unité</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($collecte->pesages as $pesage)
                            <tr>
                                <td>{{ $pesage->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $pesage->poids }}</td>
                                <td>{{ $pesage->unite }}</td>
                                <td>{{ $pesage->description ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('agent.pesages.show', $pesage) }}" class="btn btn-sm btn-outline-primary">
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

    <!-- Timeline -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Timeline des activités</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($timeline as $event)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-{{ $this->getEventColor($event['type']) }}">
                            <i class="bx {{ $event['icon'] }}"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">{{ $event['title'] }}</h6>
                            <p class="timeline-text">{{ $event['description'] }}</p>
                            <small class="text-muted">{{ $event['date']->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-marker {
        position: absolute;
        left: -22px;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    .timeline-content {
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 5px;
    }
    .timeline-title {
        margin: 0 0 5px 0;
        font-size: 14px;
        font-weight: 600;
    }
    .timeline-text {
        margin: 0 0 5px 0;
        font-size: 13px;
        color: #6c757d;
    }
</style>
@endpush

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

    function getEventColor($type) {
        return match($type) {
            'creation' => 'primary',
            'depart' => 'warning',
            'arrivee' => 'info',
            'pesage' => 'success',
            'tri' => 'secondary',
            default => 'secondary'
        };
    }
@endphp
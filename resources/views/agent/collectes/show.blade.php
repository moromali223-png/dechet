@extends('agent.layouts.app')

@section('content')
@php
    $statusColors = [
        'en_cours' => 'warning',
        'terminee' => 'success',
        'arrive_au_centre' => 'info',
        'pesee' => 'primary',
        'triee' => 'secondary',
    ];

    $eventColors = [
        'creation' => 'primary',
        'depart' => 'warning',
        'arrivee' => 'info',
        'pesage' => 'success',
        'tri' => 'secondary',
    ];
@endphp

<div class="row">

    <!-- ================= INFORMATIONS ================= -->
    <div class="col-lg-8">

        <!-- Détails collecte -->
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Détails de la collecte</h5>
                <a href="{{ route('agent.collectes.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> Retour
                </a>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <h6 class="text-muted">Client</h6>
                        <p class="fw-bold">{{ $collecte->planification?->client?->nom ?? 'N/A' }}</p>

                        <h6 class="text-muted">Collecteur</h6>
                        <p class="fw-bold">{{ $collecte->planification?->collecteur?->nom ?? 'N/A' }}</p>

                        <h6 class="text-muted">Statut</h6>
                        <span class="badge bg-label-{{ $statusColors[$collecte->statut] ?? 'secondary' }}">
                            {{ ucfirst(str_replace('_', ' ', $collecte->statut)) }}
                        </span>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted">Créé le</h6>
                        <p>{{ $collecte->created_at?->format('d/m/Y H:i') }}</p>

                        <p>
    {{ $collecte->heure_depart ? \Carbon\Carbon::parse($collecte->heure_depart)->format('d/m/Y H:i') : '-' }}
</p>

<p>
    {{ $collecte->heure_fin ? \Carbon\Carbon::parse($collecte->heure_fin)->format('d/m/Y H:i') : '-' }}
</p>
                    </div>

                </div>

                @if($collecte->commentaire)
                    <hr>
                    <h6 class="text-muted">Commentaire</h6>
                    <p class="mb-0">{{ $collecte->commentaire }}</p>
                @endif
            </div>
        </div>

        <!-- PHOTO -->
        @if($collecte->photo)
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header">
                <h6 class="mb-0">Photo de la collecte</h6>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $collecte->photo) }}"
                     class="img-fluid rounded"
                     style="max-height: 400px;">
            </div>
        </div>
        @endif

        <!-- PESAGES -->
        @if($collecte->pesages && $collecte->pesages->count())
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header">
                <h6 class="mb-0">Pesages effectués</h6>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Poids</th>
                            <th>Unité</th>
                            <th>Description</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($collecte->pesages as $pesage)
                        <tr>
                            <td>{{ $pesage->created_at?->format('d/m/Y H:i') }}</td>
                            <td><strong>{{ $pesage->poids }}</strong></td>
                            <td>{{ $pesage->unite }}</td>
                            <td>{{ $pesage->description ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('agent.pesages.show', $pesage) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-show"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        @endif

    </div>

    <!-- ================= TIMELINE ================= -->
    <div class="col-lg-4">

        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h6 class="mb-0">Timeline</h6>
            </div>

            <div class="card-body">
                <div class="timeline">

                    @foreach($timeline as $event)
                    <div class="timeline-item">

                        <div class="timeline-marker bg-{{ $eventColors[$event['type']] ?? 'secondary' }}">
                            <i class="bx {{ $event['icon'] }}"></i>
                        </div>

                        <div class="timeline-content">
                            <h6 class="mb-1">{{ $event['title'] }}</h6>
                            <p class="text-muted mb-1">{{ $event['description'] }}</p>
                            <small class="text-muted">
                                {{ $event['date'] ? \Carbon\Carbon::parse($event['date'])->format('d/m/Y H:i') : '-' }}
                            </small>
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
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}

.timeline-content {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 6px;
}
</style>
@endpush 
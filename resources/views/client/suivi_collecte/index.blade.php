@extends('layouts.app')

@section('content')

<h4 class="mb-3">Suivi de mes collectes</h4>

<h6 class="mb-1">filtre</h6>

@php
    $filters = [
        'toutes' => 'Toutes les collectes',
        'planifiee' => 'Planifiées',
        'assignee' => 'Assignées',
        'en_cours' => 'En cours',
        'terminee' => 'Terminées',
        'annulee' => 'Annulées',
    ];
@endphp

<div class="mb-3 d-flex flex-wrap gap-2">
    @foreach($filters as $key => $label)
        <a href="?type={{ $key }}"
           class="btn btn-sm {{ $type === $key ? 'btn-dark' : 'btn-outline-primary' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date prévue</th>
                    <th>Zone</th>
                    <th>Collecteur</th>
                    <th>Statut</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($collectes as $p)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($p->date_prevue)->format('d/m/Y H:i') }}</td>

                        <td>{{ $p->zone->nom ?? 'Non définie' }}</td>

                        <td>{{ $p->collecteur->user->name ?? 'Non assigné' }}</td>

                        <td>
                            @php
                                $badge = match($p->statut) {
                                    'planifiee' => 'secondary',
                                    'assignee' => 'info',
                                    'en_cours' => 'warning',
                                    'terminee' => 'success',
                                    'annulee' => 'danger',
                                    default => 'dark'
                                };

                                $labelStatut = match($p->statut) {
                                    'planifiee' => 'Planifiée',
                                    'assignee' => 'Assignée',
                                    'en_cours' => 'En cours',
                                    'terminee' => 'Terminée',
                                    'annulee' => 'Annulée',
                                    default => ucfirst($p->statut)
                                };
                            @endphp

                            <span class="badge bg-{{ $badge }}">
                                {{ $labelStatut }}
                            </span>
                        </td>

                        <td class="text-end">
                            <a href="{{ route('client.suivi_collecte.show', $p->id) }}"
                               class="btn btn-sm btn-primary">
                                Détails
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-3 text-muted">
                            Aucune collecte trouvée
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

<div class="mt-3">
    {{ $collectes->links() }}
</div>

@endsection
@extends('layouts.app')

@section('title', 'Abonnements')

@section('content')

<div class="container-fluid px-2">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold mb-1">Abonnements</h1>
            <p class="text-muted mb-0">Gestion des abonnements</p>
        </div>

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('abonnements.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Nouveau abonnement
            </a>
        @endif

    </div>

    <div class="card shadow-lg border-0">
        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Déchet</th>
                            <th>Adresse</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($abonnements as $abonnement)
                            <tr>

                                <td>{{ $abonnement->id }}</td>

                                <td>
                                    {{ $abonnement->client?->user?->name ?? $abonnement->user?->name }}
                                </td>

                                <td>{{ $abonnement->type_dechet }}</td>

                                <td>{{ $abonnement->adresse_complete }}</td>

                                <td>
                                    {{ $abonnement->date_debut->format('d/m/Y') }}
                                    →
                                    {{ $abonnement->date_fin->format('d/m/Y') }}
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($abonnement->statut) }}
                                    </span>
                                </td>

                                <td class="d-flex gap-2">

                                    {{-- TOUS PEUVENT VOIR --}}
                                    <a href="{{ route('abonnements.show', $abonnement->id) }}"
                                       class="btn btn-sm btn-info">
                                        Voir
                                    </a>

                                    {{-- ADMIN ONLY --}}
                                    @if(auth()->user()->role === 'admin')

                                        <a href="{{ route('abonnements.edit', $abonnement->id) }}"
                                           class="btn btn-sm btn-warning">
                                            Modifier
                                        </a>

                                        <form method="POST"
                                              action="{{ route('abonnements.destroy', $abonnement->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Supprimer ?')">
                                                Supprimer
                                            </button>
                                        </form>

                                    @endif

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Aucun abonnement
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
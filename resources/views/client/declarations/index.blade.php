@extends('layouts.app')

@section('title', 'Mes déclarations')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <div>
                <h4 class="mb-1">Mes déclarations</h4>
                <small class="text-muted">Suivi de toutes vos déclarations de déchets</small>
            </div>

            <a href="{{ route('declarations.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Nouvelle déclaration
            </a>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Type de déchet</th>
                            <th>Poids estimé</th>
                            <th>Abonnement</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($declarations as $declaration)
                            <tr>
                                <td>{{ $declaration->id }}</td>

                                <td>
                                    <span class="fw-semibold">
                                        {{ $declaration->type_dechet }}
                                    </span>
                                </td>

                                <td>
                                    {{ $declaration->poids_estime
                                        ? number_format($declaration->poids_estime, 2, ',', ' ') . ' kg'
                                        : '-' }}
                                </td>

                                <td>
                                    @if($declaration->abonnement)
                                        <span class="badge bg-label-info">
                                            {{ $declaration->abonnement->type_abonnement }}
                                        </span>
                                    @else
                                        <span class="badge bg-label-secondary">
                                            Manuel
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @php
                                        $badgeClass = match($declaration->statut){
                                            'en_attente' => 'bg-warning',
                                            'valide' => 'bg-success',
                                            'planifiee' => 'bg-info',
                                            'rejete' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp

                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $declaration->statut)) }}
                                    </span>
                                </td>

                                <td>{{ $declaration->created_at->format('d/m/Y') }}</td>

                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-2">

                                        {{-- Voir --}}
                                        <a href="{{ route('declarations.show', $declaration) }}"
                                           class="btn btn-sm btn-icon btn-outline-primary"
                                           title="Voir">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        {{-- Modifier --}}
                                        @if($declaration->statut === 'en_attente')
                                            <a href="{{ route('declarations.edit', $declaration) }}"
                                               class="btn btn-sm btn-icon btn-outline-warning"
                                               title="Modifier">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-icon btn-outline-secondary"
                                                    disabled
                                                    title="Modification impossible">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                        @endif

                                        {{-- Supprimer --}}
                                        @if($declaration->statut === 'en_attente')
                                            <form action="{{ route('declarations.destroy', $declaration) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-icon btn-outline-danger"
                                                        title="Supprimer"
                                                        onclick="return confirm('Supprimer cette déclaration ?')">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-icon btn-outline-secondary"
                                                    disabled
                                                    title="Suppression impossible">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        @endif

                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Aucune déclaration trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $declarations->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
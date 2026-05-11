@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des Pesages</h5>
            <a href="{{ route('pesages.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Nouveau Pesage
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Collecte</th>
                            <th>Poids</th>
                            <th>Unité</th>
                            <th>Statut</th>
                            <th>Description</th>
                            <th>Date de création</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesages as $pesage)
                            <tr>
                                <td><strong>#{{ $pesage->id }}</strong></td>
                                <td>Collecte #{{ $pesage->id_collecte }}</td>
                                <td><strong>{{ number_format($pesage->poids, 2) }}</strong></td>
                                <td>{{ $pesage->unite }}</td>
                                <td>
                                    @php
                                        $color = match($pesage->statut) {
                                            'Validé' => 'success',
                                            'Rejeté' => 'danger',
                                            default => 'warning'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $color }} px-3 py-2">
                                        {{ $pesage->statut }}
                                    </span>
                                </td>
                                <td>
                                    @if($pesage->description)
                                        {{ Str::limit($pesage->description, 60) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $pesage->created_at->format('d/m/Y à H:i') }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 flex-nowrap">
                                        <a href="{{ route('pesages.show', $pesage) }}" 
                                           class="btn btn-icon btn-sm btn-info"
                                           title="Voir">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('pesages.edit', $pesage) }}" 
                                           class="btn btn-icon btn-sm btn-warning"
                                           title="Modifier">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('pesages.destroy', $pesage) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-icon btn-sm btn-danger"
                                                    title="Supprimer"
                                                    onclick="return confirm('Voulez-vous vraiment supprimer ce pesage ?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bx bx-info-circle fs-1 text-muted"></i>
                                    <p class="mt-2 mb-0">Aucun pesage enregistré pour le moment.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $pesages->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
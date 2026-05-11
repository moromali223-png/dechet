@extends('layouts.app')

@section('title', 'Liste des Tris')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Liste des Tris</h5>
                <small class="text-muted">Gestion des déchets triés</small>
            </div>
            <a href="{{ route('tries.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Nouveau Tri
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>N°</th>
                            <th>Type de Déchet</th>
                            <th>Quantité</th>
                            <th>Unité</th>
                            <th>Qualité</th>
                            <th>Destination</th>
                            <th>Valeur Estimée</th>
                            <th>Pesage lié</th>
                            <th>Date</th>
                            <th style="width: 160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tries as $tri)
                            <tr>
                                <td><strong>#{{ $tri->id }}</strong></td>
                                <td>
                                    <span class="badge bg-label-primary">{{ $tri->type_dechet }}</span>
                                </td>
                                <td>
                                    <strong>{{ number_format($tri->quantite_trier, 2) }}</strong>
                                </td>
                                <td>{{ $tri->unite ?? 'kg' }}</td>
                                <td>
                                    @php
                                        $qualiteColor = match($tri->qualite ?? '') {
                                            'Excellent' => 'success',
                                            'Bon'       => 'primary',
                                            'Moyen'     => 'warning',
                                            'Mauvais'   => 'danger',
                                            default     => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $qualiteColor }}">
                                        {{ $tri->qualite ?? 'Non défini' }}
                                    </span>
                                </td>
                                <td>
                                    @if($tri->destination)
                                        <span class="badge bg-label-info">{{ $tri->destination }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tri->valeur_estimee)
                                        <strong>{{ number_format($tri->valeur_estimee, 0) }}</strong> FCFA
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tri->pesage)
                                        Pesage #{{ $tri->pesage->id }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $tri->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 flex-nowrap">
                                        <a href="{{ route('tries.show', $tri) }}" 
                                           class="btn btn-icon btn-sm btn-info" title="Voir">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('tries.edit', $tri) }}" 
                                           class="btn btn-icon btn-sm btn-warning" title="Modifier">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('tries.destroy', $tri) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-sm btn-danger"
                                                    onclick="return confirm('Supprimer ce tri ?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <i class="bx bx-package fs-1 text-muted"></i>
                                    <p class="mt-3 mb-0">Aucun tri enregistré pour le moment.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-4">
                {{ $tries->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
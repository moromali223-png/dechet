@extends('layouts.app')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="container-fluid px-2">   <!-- Réduit pour moins d'espace avec la sidebar -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Gestion des Paiements</h1>
            <p class="text-muted mb-0">
                Suivi et gestion de tous les paiements du système.
            </p>
        </div>

        <a href="{{ route('admin.commandes.index') }}" class="btn btn-outline-primary">
            <i class="bx bx-arrow-back me-1"></i> Retour commandes
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            <i class="bx bx-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">
            <i class="bx bx-error-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg rounded-2">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Commande</th>
                            <th>Abonnement</th>
                            <th>Mode</th>
                            <th class="text-end">Montant</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($paiements as $paiement)
                            <tr>
                                <td><strong>{{ $paiement->id }}</strong></td>

                              <td>
    @if($paiement->commande && $paiement->commande->user)
        {{ $paiement->commande->user->name ?? $paiement->commande->user->username ?? 'Utilisateur inconnu' }}
    @elseif($paiement->abonnement && $paiement->abonnement->user)
        {{ $paiement->abonnement->user->name ?? $paiement->abonnement->user->username ?? 'Utilisateur inconnu' }}
    @else
        <span class="text-muted">-</span>
    @endif
</td>
                                <td>
                                    {{ $paiement->commande->code_commande ?? '-' }}
                                </td>

                                <td>
                                    @if($paiement->abonnement_id)
                                        <span class="badge bg-info">
                                            ABO-{{ $paiement->abonnement_id }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    {{ ucfirst(str_replace('_', ' ', $paiement->mode_paiement)) }}
                                </td>

                                <td class="text-end fw-bold text-success">
                                    {{ number_format($paiement->montant, 2, ',', ' ') }} FCFA
                                </td>

                                <td>
                                    <span class="badge 
                                        {{ $paiement->statut === 'valide' ? 'bg-success' :
                                           ($paiement->statut === 'echoue' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                        {{ ucfirst(str_replace('_', ' ', $paiement->statut ?? 'en_attente')) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2 flex-nowrap">
                                        <!-- Voir -->
                                        <a href="{{ route('paiements.show', $paiement->id) }}"
                                           class="btn btn-sm btn-info rounded-pill"
                                           title="Voir">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        <!-- Modifier -->
                                        <a href="{{ route('paiements.edit', $paiement->id) }}"
                                           class="btn btn-sm btn-warning rounded-pill"
                                           title="Modifier">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <!-- Supprimer -->
                                        <form action="{{ route('paiements.destroy', $paiement->id) }}"
                                              method="POST"
                                              class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger rounded-pill"
                                                    title="Supprimer"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bx bx-credit-card display-4 text-muted"></i>
                                    <p class="mt-3 text-muted">Aucun paiement trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($paiements->hasPages())
                <div class="p-4">
                    {{ $paiements->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="bx bx-credit-card me-2"></i>Gestion des Paiements
            </h5>

            <a href="{{ route('admin.commandes.index') }}" class="btn btn-outline-primary">
                <i class="bx bx-arrow-back me-1"></i> Retour commandes
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Commande</th>
                            <th>Abonnement</th>
                            <th>Mode</th>
                            <th class="text-end">Montant</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($paiements as $paiement)
                            <tr>

                                <td>
                                    <strong>{{ $paiement->id }}</strong>
                                </td>

                                <td>
                                    @if($paiement->commande && $paiement->commande->client)
                                        {{ $paiement->commande->client->user->name ?? $paiement->commande->client->name ?? 'Client inconnu' }}
                                    @elseif($paiement->abonnement && $paiement->abonnement->client)
                                        {{ $paiement->abonnement->client->user->name ?? $paiement->abonnement->client->name ?? 'Client inconnu' }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $paiement->commande->code_commande ?? '-' }}
                                </td>

                                <td>
                                    @if($paiement->abonnement_id)
                                        <span class="badge bg-info text-dark">
                                            ABO-{{ $paiement->abonnement_id }}
                                        </span>
                                    @else
                                        -
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

                                <td>
                                    {{ $paiement->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="{{ route('paiements.show', $paiement->id) }}"
                                           class="btn btn-sm btn-info"
                                           title="Voir">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        <a href="{{ route('paiements.edit', $paiement->id) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Modifier">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <form action="{{ route('paiements.destroy', $paiement->id) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
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
                                <td colspan="9" class="text-center py-5">
                                    <i class="bx bx-credit-card display-4 text-muted"></i>
                                    <h5 class="mt-3 text-muted">Aucun paiement trouvé</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $paiements->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
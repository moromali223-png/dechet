@extends('layouts.app')

@section('title', 'Liste des paiements')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Liste des paiements</h1>
            <p class="text-muted mb-0">Gestion des paiements enregistrés dans le système.</p>
        </div>
        <a href="{{ route('paiements.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Nouveau paiement
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Commande</th>
                            <th>Abonnement</th>
                            <th>Mode de paiement</th>
                            <th class="text-end">Montant</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th class="text-center" style="width: 140px;">Actions</th>   <!-- Colonne fixe -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paiements as $paiement)
                            <tr>
                                <td><strong>{{ $paiement->id }}</strong></td>
                                <td>
                                    @if($paiement->commande && $paiement->commande->client)
                                        {{ $paiement->commande->client->user->name ?? $paiement->commande->client->name ?? 'Client inconnu' }}
                                    @elseif($paiement->abonnement && $paiement->abonnement->client)
                                        {{ $paiement->abonnement->client->user->name ?? $paiement->abonnement->client->name ?? 'Client inconnu' }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $paiement->commande->code_commande ?? '-' }}</td>
                                <td>
                                    @if($paiement->abonnement_id)
                                        <span class="badge bg-info text-dark">ABO-{{ $paiement->abonnement_id }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $paiement->mode_paiement)) }}</td>
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
                                <td>{{ $paiement->created_at->format('d/m/Y H:i') }}</td>
                                
                                <!-- Colonne Actions - Toujours alignée -->
                              <td class="text-center">
    <div class="d-flex justify-content-center gap-2">
        
        <!-- Voir -->
        <a href="{{ route('paiements.show', $paiement->id) }}" 
           class="btn btn-sm btn-info" title="Voir les détails">
            <i class="bx bx-show"></i>
        </a>
        
        <!-- Modifier -->
        <a href="{{ route('paiements.edit', $paiement->id) }}" 
           class="btn btn-sm btn-warning" title="Modifier">
            <i class="bx bx-edit"></i>
        </a>
        
        <!-- Supprimer -->
        <form action="{{ route('paiements.destroy', $paiement->id) }}" 
              method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="btn btn-sm btn-danger"
                    title="Supprimer"
                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ? Cette action est irréversible.')">
                <i class="bx bx-trash"></i>
            </button>
        </form>

    </div>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Aucun paiement trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $paiements->links() }}
    </div>
</div>
@endsection
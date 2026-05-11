@extends('agent.layouts.app')

@section('content')
<div class="row">

    <!-- STATISTIQUES -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h6>Total Tries</h6>
                <h3>{{ $stats['total_tries'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h6>Quantité totale triée</h6>
                <h3>{{ number_format($stats['quantite_totale'], 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h6>Tries aujourd’hui</h6>
                <h3>{{ $stats['tries_today'] }}</h3>
            </div>
        </div>
    </div>

</div>

<!-- TABLE -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Liste des Tries</h5>

        <a href="{{ route('agent.tries.create') }}" class="btn btn-primary">
            + Nouveau Tri
        </a>
    </div>

    <div class="card-body">

        <!-- FILTRES -->
        <form method="GET" class="row mb-3">

            <div class="col-md-4">
                <input type="text" name="type_dechet" class="form-control"
                       placeholder="Type de déchet"
                       value="{{ request('type_dechet') }}">
            </div>

            <div class="col-md-3">
                <input type="date" name="date_debut" class="form-control"
                       value="{{ request('date_debut') }}">
            </div>

            <div class="col-md-3">
                <input type="date" name="date_fin" class="form-control"
                       value="{{ request('date_fin') }}">
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filtrer</button>
            </div>

        </form>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">

                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type Déchet</th>
                        <th>Quantité</th>
                        <th>Unité</th>
                        <th>Qualité</th>
                        <th>Pesage</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tries as $tri)
                        <tr>
                            <td>{{ $tri->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $tri->type_dechet }}</td>
                            <td>{{ $tri->quantite_trier }}</td>
                            <td>{{ $tri->unite }}</td>
                            <td>{{ $tri->qualite ?? '-' }}</td>

                            <td>
                                {{ $tri->pesage->collecte->planification->abonnement->client->nom ?? 'N/A' }}
                            </td>

                            <td>
                                <a href="{{ route('agent.tries.show', $tri) }}" class="btn btn-sm btn-info">
                                    Voir
                                </a>

                                <a href="{{ route('agent.tries.edit', $tri) }}" class="btn btn-sm btn-warning">
                                    Modifier
                                </a>

                                <form action="{{ route('agent.tries.destroy', $tri) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Supprimer ce tri ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                Aucun tri trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- PAGINATION -->
        <div class="mt-3">
            {{ $tries->links() }}
        </div>

    </div>
</div>
@endsection
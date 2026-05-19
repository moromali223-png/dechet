@extends('agent.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    Rapport Journalier - {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                </h5>
                <a href="{{ route('agent.rapports.journalier', ['date' => $date, 'format' => 'pdf']) }}" 
                   class="btn btn-danger btn-sm">
                    <i class="bx bx-download"></i> Télécharger PDF
                </a>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <!-- Statistiques -->
                    <div class="col-md-3 col-6">
                        <div class="card bg-label-primary text-center">
                            <div class="card-body">
                                <h4 class="mb-1">{{ $collectes }}</h4>
                                <p class="mb-0">Collectes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card bg-label-info text-center">
                            <div class="card-body">
                                <h4 class="mb-1">{{ $pesages->count() }}</h4>
                                <p class="mb-0">Pesages</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card bg-label-success text-center">
                            <div class="card-body">
                                <h4 class="mb-1">{{ number_format($poids_total, 2) }} Kg</h4>
                                <p class="mb-0">Poids total</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card bg-label-warning text-center">
                            <div class="card-body">
                                <h4 class="mb-1">{{ $quantite_triee }}</h4>
                                <p class="mb-0">Quantité triée</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Détails Pesages -->
                @if($pesages->isNotEmpty())
                <h6 class="mt-5 mb-3">Détail des Pesages</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Poids (Kg)</th>
                                <th>Commentaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesages as $pesage)
                            <tr>
                                <td>{{ $pesage->id }}</td>
                                <td>{{ $pesage->created_at->format('H:i:s') }}</td>
                                <td><strong>{{ $pesage->poids }}</strong></td>
                                <td>{{ $pesage->commentaire ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Détails Tries -->
                @if($tries->isNotEmpty())
                <h6 class="mt-5 mb-3">Détail des Triés</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Quantité</th>
                                <th>Type</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tries as $trie)
                            <tr>
                                <td>{{ $trie->id }}</td>
                                <td><strong>{{ $trie->quantite_trier }}</strong></td>
                                <td>{{ $trie->type ?? '-' }}</td>
                                <td>{{ $trie->created_at->format('H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if($pesages->isEmpty() && $tries->isEmpty() && $collectes == 0)
                <div class="alert alert-info text-center py-5">
                    <i class="bx bx-info-circle bx-lg mb-3"></i>
                    <p>Aucune activité enregistrée pour cette date.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
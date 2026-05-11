@extends('agent.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Rapports et statistiques</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Rapport journalier -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="avatar avatar-xl mx-auto mb-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="bx bx-calendar bx-lg"></i>
                                    </div>
                                </div>
                                <h5 class="card-title">Rapport journalier</h5>
                                <p class="card-text text-muted">Consultez les activités et statistiques de la journée</p>
                                <form action="{{ route('agent.rapports.journalier') }}" method="GET" class="d-inline">
                                    <input type="date" name="date" value="{{ today()->format('Y-m-d') }}" class="form-control d-inline-block w-auto me-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-show me-1"></i>
                                        Voir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Rapport mensuel -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="avatar avatar-xl mx-auto mb-3">
                                    <div class="avatar-initial bg-label-success rounded">
                                        <i class="bx bx-calendar-week bx-lg"></i>
                                    </div>
                                </div>
                                <h5 class="card-title">Rapport mensuel</h5>
                                <p class="card-text text-muted">Analysez les performances du mois</p>
                                <form action="{{ route('agent.rapports.mensuel') }}" method="GET" class="d-inline">
                                    <input type="month" name="mois" value="{{ now()->format('Y-m') }}" class="form-control d-inline-block w-auto me-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bx bx-show me-1"></i>
                                        Voir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Export options -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Options d'export</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <form action="{{ route('agent.rapports.journalier') }}" method="GET">
                                            <input type="hidden" name="format" value="pdf">
                                            <input type="date" name="date" value="{{ today()->format('Y-m-d') }}" class="form-control mb-2">
                                            <button type="submit" class="btn btn-outline-danger w-100">
                                                <i class="bx bx-download me-1"></i>
                                                Rapport journalier PDF
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-4">
                                        <form action="{{ route('agent.rapports.mensuel') }}" method="GET">
                                            <input type="hidden" name="format" value="pdf">
                                            <input type="month" name="mois" value="{{ now()->format('Y-m') }}" class="form-control mb-2">
                                            <button type="submit" class="btn btn-outline-danger w-100">
                                                <i class="bx bx-download me-1"></i>
                                                Rapport mensuel PDF
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-outline-info w-100" disabled>
                                            <i class="bx bx-download me-1"></i>
                                            Export Excel (Bientôt)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
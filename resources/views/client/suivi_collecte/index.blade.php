@extends('layouts.app')

@section('content')

<h4>Suivi de mes collectes</h4>

<div class="row mb-3">
    <div class="col">
        <a href="?type=toutes" class="btn btn-primary btn-sm">Toutes</a>
        <a href="?type=en_cours" class="btn btn-warning btn-sm">En cours</a>
        <a href="?type=terminees" class="btn btn-success btn-sm">Terminées</a>
        <a href="?type=annulees" class="btn btn-danger btn-sm">Annulées</a>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Date prévue</th>
                    <th>Zone</th>
                    <th>Collecteur</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($collectes as $p)
                    <tr>
                        <td>{{ $p->date_prevue }}</td>
                        <td>{{ $p->zone->nom ?? '-' }}</td>
                        <td>{{ $p->collecteur->user->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $p->statut }}</span>
                        </td>
                        <td>
                            <a href="{{ route('client.suivi_collecte.show', $p->id) }}"
                               class="btn btn-sm btn-primary">
                               Voir
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

{{ $collectes->links() }}

@endsection
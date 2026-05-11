@extends('layouts.app')

@section('title', 'Détails du Tri')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Détails du Tri #{{ $tri->id }}</h5>
            <a href="{{ route('tries.index') }}" class="btn btn-secondary">Retour à la liste</a>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Informations générales</h6>
                    <table class="table table-borderless">
                        <tr>
                            <th>ID:</th>
                            <td>{{ $tri->id }}</td>
                        </tr>
                        <tr>
                            <th>Pesage associé:</th>
                            <td>
                                @if($tri->pesage)
                                    <a href="{{ route('pesages.show', $tri->pesage->id) }}">{{ $tri->pesage->id }} - {{ $tri->pesage->date_pesage }}</a>
                                @else
                                    Non défini
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Type de déchet:</th>
                            <td>{{ $tri->type_dechet }}</td>
                        </tr>
                        <tr>
                            <th>Quantité triée:</th>
                            <td>{{ $tri->quantite_trier }} {{ $tri->unite }}</td>
                        </tr>
                        <tr>
                            <th>Qualité:</th>
                            <td>
                                <span class="badge bg-{{ $tri->qualite == 'Excellent' ? 'success' : ($tri->qualite == 'Bon' ? 'primary' : ($tri->qualite == 'Moyen' ? 'warning' : 'danger')) }}">
                                    {{ $tri->qualite }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted">Informations complémentaires</h6>
                    <table class="table table-borderless">
                        <tr>
                            <th>Destination:</th>
                            <td>{{ $tri->destination ?? 'Non définie' }}</td>
                        </tr>
                        <tr>
                            <th>Valeur estimée:</th>
                            <td>{{ $tri->valeur_estimee ? $tri->valeur_estimee . ' €' : 'Non définie' }}</td>
                        </tr>
                        <tr>
                            <th>Notes:</th>
                            <td>{{ $tri->notes ?? 'Aucune note' }}</td>
                        </tr>
                        <tr>
                            <th>Créé le:</th>
                            <td>{{ $tri->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Modifié le:</th>
                            <td>{{ $tri->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('tries.edit', $tri) }}" class="btn btn-primary">Modifier</a>
                <form action="{{ route('tries.destroy', $tri) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce tri ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
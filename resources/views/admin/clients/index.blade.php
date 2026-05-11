@extends('layouts.app')

@section('title', 'Liste des Clients')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Liste des clients</h1>
            <p class="text-muted mb-0">Gestion des clients enregistrés dans le système.</p>
        </div>
        <a href="{{ route('clients.create') }}" class="btn btn-primary">
            Ajouter un client
        </a>
    </div>
    

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Zone</th>
                <th>Type Client</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

        @forelse($clients as $client)
            <tr>
                <td>{{ $client->user->name }}</td>
                <td>{{ $client->user->email }}</td>
                <td>{{ $client->user->telephone }}</td>
                <td>{{ $client->zone->nom ?? 'Non défini' }}</td>
                <td>{{ $client->typeclient }}</td>
                <td>{{ $client->longitude }}</td>
                <td>{{ $client->latitude }}</td>

               <td>
    <div class="d-flex align-items-center gap-2 flex-nowrap">

        {{-- Voir --}}
        <a href="{{ route('clients.show', $client->id) }}"
           class="btn btn-sm btn-info"
           title="Voir">
            <i class="bx bx-show"></i>
        </a>

        {{-- Modifier --}}
        <a href="{{ route('clients.edit', $client->id) }}"
           class="btn btn-sm btn-warning"
           title="Modifier">
            <i class="bx bx-edit"></i>
        </a>

        {{-- Supprimer --}}
        <form action="{{ route('clients.destroy', $client->id) }}"
              method="POST" class="m-0">
            @csrf
            @method('DELETE')

            <button type="submit"
                class="btn btn-sm btn-danger"
                title="Supprimer"
                onclick="return confirm('Voulez-vous vraiment supprimer ce client ?')">
                <i class="bx bx-trash"></i>
            </button>
        </form>

    </div>
</td>
            </tr>

        @empty
            <tr>
                <td colspan="8" class="text-center">
                    Aucun client trouvé
                </td>
            </tr>
        @endforelse

        </tbody>
    </table>

</div>
@endsection
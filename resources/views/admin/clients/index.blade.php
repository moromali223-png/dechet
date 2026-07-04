@extends('layouts.app')

@section('title', 'Liste des Clients')

@section('content')

<div class="container-fluid px-2">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Liste des Clients</h1>
            <p class="text-muted mb-0">
                Gestion des clients enregistrés dans le système.
            </p>
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

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Zone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->telephone }}</td>
                                <td>{{ $client->zone->nom ?? 'Non défini' }}</td>

                                  <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2 flex-nowrap">
                                        
                                        <!-- Voir -->
                                        <a href="{{ route('clients.show', $client->id) }}"
                                           class="btn btn-sm btn-info rounded-pill"
                                           title="Voir">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        <!-- Modifier -->
                                        <a href="{{ route('clients.edit', $client->id) }}"
                                           class="btn btn-sm btn-warning rounded-pill"
                                           title="Modifier">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <!-- Supprimer -->
                                        <form action="{{ route('clients.destroy', $client->id) }}"
                                              method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger rounded-pill"
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
                                <td colspan="5" class="text-center">Aucun client trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="p-3">
                {{ $clients->links() }}
            </div>

        </div>
    </div>
</div>

@endsection
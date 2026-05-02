@extends('layouts.app')

@section('title', 'Liste des Tris')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Liste des tris</h1>
            <p class="text-muted">Gestion des déchets triés</p>
        </div>

        <a href="{{ route('tries.create') }}" class="btn btn-primary">
            Nouveau tri
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-hover table-bordered">
        <thead >
            <tr>
                <th>N°</th>
                <th>Type de déchet</th>
                <th>Quantité</th>
                <th>Unité</th>
                <th>Pesage</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        @forelse($tries as $tri)
            <tr>
                <td>{{ $tri->id }}</td>

                <td>
                    
                        {{ $tri->type_dechet }}
                    
                </td>

                <td>
                    <strong>{{ $tri->quantite_trier }}</strong>
                </td>

                <td>{{ $tri->unite }}</td>

                <td>
                    {{ $tri->pesage->id ?? 'N/A' }}
                </td>

                <td>
                    {{ $tri->created_at->format('d/m/Y') }}
                </td>

                <td>
                    <div class="d-flex gap-2">

                        <a href="{{ route('tries.show', $tri->id) }}"
                           class="btn btn-sm btn-info">
                           <i class="bx bx-show"></i>
                        </a>

                        <a href="{{ route('tries.edit', $tri->id) }}"
                           class="btn btn-sm btn-warning">
                           <i class="bx bx-edit"></i>
                        </a>

                        <form action="{{ route('tries.destroy', $tri->id) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Supprimer ce tri ?')">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>

                    </div>
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

    {{ $tries->links() }}

</div>
@endsection
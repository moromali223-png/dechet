@extends('layouts.app')

@section('title', 'Abonnements')

@section('content')

<div class="container-fluid px-2">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold mb-1">Abonnements</h1>
            <p class="text-muted mb-0">Gestion des abonnements</p>
        </div>

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('abonnements.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Nouveau abonnement
            </a>
        @endif

    </div>

    <div class="card shadow-lg border-0">
        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

            
<thead class="table-light">
    <tr>
        <th><i class=""></i> ID</th>
        <th><i class="bx bx-user"></i> Client</th>
        <th><i class="bx bx-recycle"></i> Déchet</th>
        <th><i class="bx bx-map"></i> Adresse</th>
        <th><i class="bx bx-calendar"></i> Période</th>
        <th><i class="bx bx-check-shield"></i> Statut</th>
        <th class="text-center">
            <i class="bx bx-cog"></i> Actions
        </th>
    </tr>
</thead>

<tbody>

@forelse($abonnements as $abonnement)
<tr>

    <td>
        <strong>{{ $abonnement->id }}</strong>
    </td>

    <td>
        <strong>
            {{ $abonnement->user?->name ?? $abonnement->user?->name }}
        </strong>
    </td>

    <td>
        <span class="badge bg-info">
            {{ $abonnement->type_dechet }}
        </span>
    </td>

    <td>{{ $abonnement->adresse_complete }}</td>

    <td>
        <small class="text-muted">
            {{ $abonnement->date_debut->format('d/m/Y') }}
            →
            {{ $abonnement->date_fin->format('d/m/Y') }}
        </small>
    </td>

    <td>
        @if($abonnement->statut == 'actif')
            <span class="badge bg-success">
                <i class="bx bx-check-circle"></i>
                Actif
            </span>

        @elseif($abonnement->statut == 'expiré')
            <span class="badge bg-danger">
                <i class="bx bx-x-circle"></i>
                Expiré
            </span>

        @else
            <span class="badge bg-warning text-dark">
                <i class="bx bx-time"></i>
                {{ ucfirst($abonnement->statut) }}
            </span>
        @endif
    </td>

    <td class="text-center">
        <div class="d-flex align-items-center justify-content-center gap-2 flex-nowrap">

            <!-- Voir -->
            <a href="{{ route('abonnements.show', $abonnement->id) }}"
               class="btn btn-sm btn-info rounded-pill"
               title="Voir">
                <i class="bx bx-show"></i>
            </a>

            @if(auth()->user()->role === 'admin')

                <!-- Modifier -->
                <a href="{{ route('abonnements.edit', $abonnement->id) }}"
                   class="btn btn-sm btn-warning rounded-pill"
                   title="Modifier">
                    <i class="bx bx-edit"></i>
                </a>

                <!-- Supprimer -->
                <form method="POST"
                      action="{{ route('abonnements.destroy', $abonnement->id) }}"
                      class="m-0">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-sm btn-danger rounded-pill"
                            title="Supprimer"
                            onclick="return confirm('Voulez-vous vraiment supprimer cet abonnement ?')">
                        <i class="bx bx-trash"></i>
                    </button>
                </form>

            @endif

        </div>
    </td>

</tr>

@empty
<tr>
    <td colspan="7" class="text-center py-5">
        <i class="bx bx-calendar-x display-4 text-muted"></i>
        <p class="mt-3 text-muted">
            Aucun abonnement trouvé
        </p>
    </td>
</tr>
@endforelse

</tbody>
```

                </table>

            </div>

        </div>
    </div>

</div>

@endsection
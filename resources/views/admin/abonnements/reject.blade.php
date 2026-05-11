@extends('layouts.app')

@section('title', 'Rejeter un abonnement')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="bx bx-x-circle me-2"></i>
                        Rejeter l'abonnement
                    </h4>
                </div>

                <div class="card-body">
                    <!-- Informations de l'abonnement -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading mb-2">
                            <i class="bx bx-info-circle me-1"></i>
                            Détails de l'abonnement
                        </h6>
                        <p class="mb-1"><strong>Client :</strong> {{ $abonnement->user->name ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Type :</strong> {{ $abonnement->type_abonnement }}</p>
                        <p class="mb-1"><strong>Déchet :</strong> {{ $abonnement->type_dechet }}</p>
                        <p class="mb-0"><strong>Période :</strong> {{ $abonnement->date_debut->format('d/m/Y') }} - {{ $abonnement->date_fin->format('d/m/Y') }}</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6 class="alert-heading mb-2">
                                <i class="bx bx-error-circle me-1"></i>
                                Veuillez corriger les erreurs suivantes :
                            </h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('abonnements.rejeter', $abonnement->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="motif_rejet" class="form-label">
                                Motif de rejet <span class="text-danger">*</span>
                            </label>
                            <textarea
                                id="motif_rejet"
                                name="motif_rejet"
                                class="form-control @error('motif_rejet') is-invalid @enderror"
                                rows="4"
                                placeholder="Expliquez la raison du rejet de cet abonnement..."
                                required
                            >{{ old('motif_rejet') }}</textarea>
                            <div class="form-text">
                                Ce motif sera envoyé par email au client pour expliquer la décision.
                            </div>
                            @error('motif_rejet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('abonnements.show', $abonnement->id) }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>
                                Annuler
                            </a>

                            <button type="submit" class="btn btn-danger">
                                <i class="bx bx-x me-1"></i>
                                Rejeter l'abonnement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
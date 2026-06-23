@extends('layouts.app')

@section('title', 'Ajouter une zone')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">
        <h4 class="mb-1 fw-bold">
            Ajouter une zone
        </h4>
        <small class="text-muted">
            Définir une nouvelle zone de collecte dans le système
        </small>
    </div>

    <div class="card-body">

        {{-- ERREURS --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        <form action="{{ route('zones.store') }}" method="POST">
            @csrf

            <div class="row g-3">

                {{-- ================= INFOS ZONE ================= --}}
                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bx bx-map me-2"></i>
                        Informations de la zone
                    </h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nom de la zone</label>
                    <input type="text"
                           name="nom"
                           class="form-control"
                           value="{{ old('nom') }}"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ville</label>
                    <input type="text"
                           name="ville"
                           class="form-control"
                           value="{{ old('ville') }}"
                           required>
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              rows="3"
                              class="form-control"
                              placeholder="Décrire la zone (quartiers, limites, observations...)">
                        {{ old('description') }}
                    </textarea>
                </div>

            </div>

            {{-- BOUTONS --}}
            <div class="mt-4 d-flex justify-content-end gap-2">

                <a href="{{ route('zones.index') }}"
                   class="btn btn-light">
                    Annuler
                </a>

                <button type="submit"
                        class="btn btn-primary">
                    <i class="bx bx-save me-1"></i>
                    Enregistrer la zone
                </button>

            </div>

        </form>

    </div>
</div>

@endsection
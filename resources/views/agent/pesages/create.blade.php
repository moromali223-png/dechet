@extends('agent.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Nouveau pesage</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('agent.pesages.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_collecte" class="form-label">Collecte *</label>
                                <select name="id_collecte" id="id_collecte" class="form-select" required>
                                    <option value="">Sélectionner une collecte</option>
                                    @foreach($collectes as $collecte)
                                        <option value="{{ $collecte->id }}">
                                            {{ $collecte->planification->client->nom ?? 'N/A' }} - {{ $collecte->created_at->format('d/m/Y') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="poids" class="form-label">Poids *</label>
                                <input type="number" step="0.01" name="poids" id="poids" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="unite" class="form-label">Unité *</label>
                                <select name="unite" id="unite" class="form-select" required>
                                    <option value="kg">kg</option>
                                    <option value="g">g</option>
                                    <option value="t">t</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i>
                            Enregistrer
                        </button>
                        <a href="{{ route('agent.pesages.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i>
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
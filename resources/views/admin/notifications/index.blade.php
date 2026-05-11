@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Notifications</h4>
        </div>
        <div class="card-body">
            <p class="text-muted">Notifications simples pour informer l’utilisateur des événements importants.</p>

            <div class="list-group">
                @forelse($notifications as $notification)
                    <div class="list-group-item d-flex align-items-center gap-3">
                        <span class="badge bg-primary rounded-circle p-2">{{ $notification->icon }}</span>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $notification->message }}</div>
                            <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-center text-muted">
                        Aucune notification pour le moment.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

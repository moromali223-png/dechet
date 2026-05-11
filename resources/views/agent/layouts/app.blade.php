@extends('layouts.app')

@section('title', 'EcoFlux - Agent Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
<style>
    .stats-card {
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-2px);
    }
    .activity-item {
        border-left: 3px solid #696cff;
        padding-left: 10px;
        margin-bottom: 10px;
    }
    .badge-status {
        font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
    // Graphiques Chart.js ou ApexCharts peuvent être ajoutés ici
</script>
@endpush
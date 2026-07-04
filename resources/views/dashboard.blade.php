@extends('layouts.app')
@section('title', 'Tableau de bord - EcoFlux')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Header Professionnel -->
   <div class="dashboard-header">
    <div class="row align-items-center g-4">
        <!-- Section de bienvenue -->
        <div class="col-lg-6 col-12">
            <div class="welcome-section"@
                <h1 class="welcome-title">
                    Bonjour, {{ auth()->user()->name ?? 'Utilisateur' }}
                </h1>
                <p class="welcome-subtitle">
                    Bienvenue sur votre tableau de bord EcoFlux. Suivez l'activité de votre plateforme en temps réel.
                </p>
            </div>
        </div>

        <!-- Header Actions : Notifications + Profil -->
        <div class="col-lg-6 col-12">
            <div class="header-actions">
                <!-- Notifications -->
                <div class="top-icon" data-bs-toggle="tooltip" title="Notifications">
                    <i class="bx bx-bell"></i>
                    <span class="badge">3</span>
                </div>

<!-- ============================
     MENU UTILISATEUR CORRIGÉ
     ============================ -->
<div class="dropdown">
    <a href="#"
       class="user-menu dropdown-toggle"
       id="userDropdown"
       role="button"
       data-bs-toggle="dropdown"
       aria-expanded="false">
       
        <img src="{{ auth()->user()->avatar ?? asset('assets/img/avatars/1.png') }}"
             alt="Avatar"
             class="user-avatar">

        <span class="d-none d-md-inline">
            {{ auth()->user()->name ?? 'Utilisateur' }}
        </span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow border-0"
        aria-labelledby="userDropdown">

        <!-- Informations utilisateur -->
        <li class="px-3 py-3">
            <div class="d-flex align-items-center">
                <img src="{{ auth()->user()->avatar ?? asset('assets/img/avatars/1.png') }}"
                     alt="Avatar"
                     class="user-avatar me-3">

                <div>
                    <h6 class="mb-0 fw-bold">
                        {{ auth()->user()->name ?? 'Utilisateur' }}
                    </h6>
                    <small class="text-muted">
                        {{ auth()->user()->email ?? 'email@example.com' }}
                    </small>
                </div>
            </div>
        </li>

        <li><hr class="dropdown-divider"></li>

        <!-- Profil -->
        <li>
            <a class="dropdown-item"
               href="{{ route('profile.edit') }}">
                <i class="bx bx-user me-2"></i>
                Mon profil
            </a>
        </li>

        <!-- Paramètres -->
        <li>
            <a class="dropdown-item"
               href="{{ route('profile.edit') }}">
                <i class="bx bx-cog me-2"></i>
                Paramètres
            </a>
        </li>

        <!-- Notifications -->
        <li>
            <a class="dropdown-item"
               href="#">
                <i class="bx bx-bell me-2"></i>
                Notifications
            </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        <!-- Déconnexion -->
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="dropdown-item text-danger">
                    <i class="bx bx-log-out me-2"></i>
                    Déconnexion
                </button>
            </form>
        </li>
    </ul>
</div>
            </div>
        </div>

        <!-- Barre de recherche -->
        <div class="col-12">
            <div class="search-modern">
                <i class="bx bx-search"></i>
                <input type="text" 
                       placeholder="Rechercher par commandes, clients, zones..." 
                       class="form-control border-0"
                       id="globalSearch">
            </div>
        </div>
    </div>
</div>
    <!-- KPI Cards -->
    <div class="row g-4">
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Clients actifs</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($clientsCount, 0, ',', ' ') }}</h2>
                            <p class="text-muted small mb-0">Total des clients inscrits</p>
                        </div>
                        <div class="avatar bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-user fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Commandes acceptées</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($commandesPending, 0, ',', ' ') }}</h2>
                            <p class="text-muted small mb-0">Commandes à traiter</p>
                        </div>
                        <div class="avatar bg-warning bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-time fs-1 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Paiements validés</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($paiementsValidCount, 0, ',', ' ') }}</h2>
                            <p class="text-muted small mb-0">Transactions traitées</p>
                        </div>
                        <div class="avatar bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-credit-card fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="text-muted small">Chiffre d’affaires</span>
                            <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</h2>
                            <p class="text-muted small mb-0">Paiements validés</p>
                        </div>
                        <div class="avatar bg-info bg-opacity-10 p-3 rounded-3">
                            <i class="bx bx-trending-up fs-1 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4 mt-4">
        <!-- Revenu sur 12 mois - Diagramme en bandes (Column Chart) -->
        <div class="col-xl-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header border-0 bg-transparent d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 text-dark">Revenu sur 12 mois</h5>
                        <small class="text-muted">Basé sur les paiements validés</small>
                    </div>
                    <span class="badge bg-success fs-6">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="card-body">
                    <div id="monthlyRevenueChart" style="height: 360px;"></div>
                </div>
            </div>
        </div>

        <!-- Statut des commandes -->
        <div class="col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header border-0 bg-transparent">
                    <h5 class="mb-0 text-dark">Statut des commandes</h5>
                    <small class="text-muted">Répartition des commandes</small>
                </div>
                <div class="card-body">
                    <div id="commandStatusChart" style="height: 280px;"></div>
                    <div class="row row-cols-2 g-3 mt-4">
                        @foreach($commandesStatusLabels as $index => $label)
                            <div class="col">
                                <div class="d-flex align-items-center gap-3 p-3 rounded-3 border bg-light">
                                    <span class="badge rounded-pill bg-success">{{ $commandesStatusData[$index] }}</span>
                                    <div>
                                        <div class="fw-semibold">{{ $label }}</div>
                                        <small class="text-muted">Commandes</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières commandes + Vue rapide -->
    <div class="row g-4 mt-4">
        <!-- ... ton code des dernières commandes et vue rapide reste identique ... -->
    </div>
</div>

@push('scripts')
<script>
// Initialiser les tooltips Bootstrap
document.addEventListener('DOMContentLoaded', function () {
    // ==========================================
    // Initialisation des Tooltips Bootstrap
    // ==========================================
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // ==========================================
    // Recherche Globale
    // ==========================================
    const globalSearch = document.getElementById('globalSearch');

    if (globalSearch) {
        globalSearch.addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase().trim();

            if (searchTerm.length > 2) {
                console.log('Recherche :', searchTerm);
                // Ajoutez ici votre logique AJAX ou de filtrage
            }
        });

        globalSearch.addEventListener('focus', function () {
            this.parentElement.classList.add('search-focused');
        });

        globalSearch.addEventListener('blur', function () {
            this.parentElement.classList.remove('search-focused');
        });
    }

    // ==========================================
    // Animation des icônes de notification
    // ==========================================
    const topIcons = document.querySelectorAll('.top-icon');

    topIcons.forEach(function (icon) {
        icon.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-3px) scale(1.05)';
        });

        icon.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // ==========================================
    // Animation du menu utilisateur
    // ==========================================
    const userMenu = document.querySelector('.user-menu');

    if (userMenu) {
        userMenu.addEventListener('mouseenter', function () {
            if (!this.classList.contains('show')) {
                this.style.transform = 'translateY(-2px)';
            }
        });

        userMenu.addEventListener('mouseleave', function () {
            if (!this.classList.contains('show')) {
                this.style.transform = 'translateY(0)';
            }
        });
    }

    // ==========================================
    // Animation d'apparition
    // ==========================================
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    const animatedElements = document.querySelectorAll(
        '.search-modern, .top-icon, .user-menu'
    );

    animatedElements.forEach(function (element) {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'all 0.6s ease';
        observer.observe(element);
    });
});

// ==========================================
// Fonction Toast Notifications
// ==========================================
function showNotification(message, type = 'info') {
    const toast = document.createElement('div');

    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"
                    aria-label="Fermer">
            </button>
        </div>
    `;

    let toastContainer = document.querySelector('.toast-container');

    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className =
            'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1080';
        document.body.appendChild(toastContainer);
    }

    toastContainer.appendChild(toast);

    const bsToast = new bootstrap.Toast(toast, {
        delay: 5000
    });

    bsToast.show();

    toast.addEventListener('hidden.bs.toast', function () {
        toast.remove();
    });
}
</script>
@endpush

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Diagramme en bandes - Revenu sur 12 mois en FCFA
        const monthlyRevenueSeries = @json($monthlyRevenue->pluck('amount'));
        const monthlyRevenueLabels = @json($monthlyRevenue->pluck('month'));
        
        const revenueChartOptions = {
            chart: {
                type: 'bar',
                height: 360,
                toolbar: { show: false }
            },
            series: [{
                name: 'Revenu (FCFA)',
                data: monthlyRevenueSeries
            }],
            colors: ['#2E7D32'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '65%',
                    borderRadius: 6,
                    dataLabels: { position: 'top' }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val ? val.toLocaleString('fr-FR') : '0';
                },
                offsetY: -20,
                style: { fontSize: '12px', colors: ['#2E7D32'] }
            },
            xaxis: {
                categories: monthlyRevenueLabels,
                labels: { style: { fontSize: '13px' } }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return value ? value.toLocaleString('fr-FR') + ' FCFA' : '0 FCFA';
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return value ? value.toLocaleString('fr-FR') + ' FCFA' : '0 FCFA';
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 4
            }
        };

        new ApexCharts(document.querySelector('#monthlyRevenueChart'), revenueChartOptions).render();

        // Donut Statut des commandes
        const commandStatusData = @json($commandesStatusData);
        const commandStatusLabels = @json($commandesStatusLabels);
        
        const statusChartOptions = {
            chart: { type: 'donut', height: 280 },
            series: commandStatusData,
            labels: commandStatusLabels,
            colors: ['#2E7D32', '#34C759', '#FFC107', '#DC3545'],
            legend: { position: 'bottom' }
        };
        new ApexCharts(document.querySelector('#commandStatusChart'), statusChartOptions).render();
    });
</script>
@endpush
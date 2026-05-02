<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo d-flex justify-content-center mb-3">
        <a href="{{ route('dashboard.agent') }}" class="app-brand-link">
            <span class="app-brand-logo">
                <img src="{{ asset('assets/img/EcoFlux1.svg') }}"
                     alt="Logo EcoFlux"
                     style="width: 240px; height: auto;">
            </span>
        </a>

        <a href="javascript:void(0);"
           class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

<!-- resources/views/partials/sidebar-client.blade.php -->
<!-- resources/views/partials/sidebar-client.blade.php -->

<ul class="menu-inner py-1">

    <!-- Tableau de bord -->
    <li class="menu-item">
        <a href="{{ route('dashboard.client') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div>Tableau de bord</div>
        </a>
    </li>

    <!-- Mes déclarations -->
    <li class="menu-item">
        <a href="{{ route('declarations.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-clipboard"></i>
            <div>Mes Déclarations</div>
        </a>
    </li>

    <!-- Suivi des collectes -->
    <li class="menu-item {{ Request::routeIs('suivi_collecte.index') ? 'active' : '' }}">
        <a href="{{ route('suivi_collecte.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-map-alt"></i>
            <div>Suivi des Collectes</div>
        </a>
    </li>

    <!-- Mon abonnement -->
    <li class="menu-item {{ Request::routeIs('abonnements.index') ? 'active' : '' }}">
        <a href="{{ route('abonnements.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-badge-check"></i>
            <div>Mon Abonnement</div>
        </a>
    </li>

    <!-- Boutique écologique -->
    <li class="menu-item">
        <a href="" class="menu-link">
            <i class="menu-icon tf-icons bx bx-store-alt"></i>
            <div>Produits Disponibles</div>
        </a>
    </li>

    <!-- Mon compte -->
    <li class="menu-item">
        <a href="" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user-circle"></i>
            <div>Mon Compte</div>
        </a>
    </li>

</ul>
</aside>
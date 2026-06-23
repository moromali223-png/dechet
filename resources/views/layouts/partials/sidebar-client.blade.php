<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo d-flex justify-content-center mb-3">
        <a href="{{ route('dashboard.client') }}" class="app-brand-link">
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

    <ul class="menu-inner py-1">

    <!-- Tableau de bord -->
    <li class="menu-item {{ request()->routeIs('dashboard.client') ? 'active' : '' }}">
        <a href="{{ route('dashboard.client') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div>Tableau de bord</div>
        </a>
    </li>

    <!-- Mes déclarations -->
    <li class="menu-item {{ request()->routeIs('declarations.*') ? 'active' : '' }}">
        <a href="{{ route('declarations.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-clipboard"></i>
            <div>Mes Déclarations</div>
        </a>
    </li>

    <!-- Suivi des collectes -->
    <li class="menu-item {{ request()->routeIs('client.suivi_collecte.*') ? 'active' : '' }}">
        <a href="{{ route('client.suivi_collecte.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-map-alt"></i>
            <div>Suivi des Collectes</div>
        </a>
    </li>

    <!-- Mon abonnement -->
    <li class="menu-item {{ request()->routeIs('abonnements.index') ? 'active' : '' }}">
        <a href="{{ route('abonnements.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-badge-check"></i>
            <div>Mon Abonnement</div>
        </a>
    </li>

    <!-- Boutique écologique -->
    <li class="menu-item {{ request()->routeIs('client.produits.*') ? 'active' : '' }}">
        <a href="{{ route('client.produits.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-store"></i>
            <div>Produits recyclés</div>
        </a>
    </li>

    <!-- MES COMMANDES -->
    <li class="menu-item {{ request()->routeIs('client.commandes.*') ? 'active' : '' }}">
        <a href="{{ route('client.commandes.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-cart"></i>
            <div>Mes commandes</div>
        </a>
    </li>

    <!-- Mon compte -->
    <li class="menu-item {{ request()->routeIs('client.compte.*') ? 'active' : '' }}">
        <a href="{{ route('client.compte.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user-circle"></i>
            <div>Mon Compte</div>
        </a>
    </li>

</ul>
</aside>
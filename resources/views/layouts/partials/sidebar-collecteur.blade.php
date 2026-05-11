<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- LOGO -->
    <div class="app-brand demo d-flex justify-content-center mb-3">
        <a href="{{ route('dashboard.collecteur') }}" class="app-brand-link">
            <img src="{{ asset('assets/img/EcoFlux1.svg') }}" style="width: 180px;">
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <!-- DASHBOARD -->
        <li class="menu-item">
            <a href="{{ route('dashboard.collecteur') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div>Tableau de bord</div>
            </a>
        </li>

     

        <li class="menu-item">
            <a href="{{ route('collecteur.tournees') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                <div>Mes tournées d’aujourd’hui</div>
            </a>
        </li>

        
        <li class="menu-item">
            <a href="{{ route('collecteur.collecte.encours') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-play-circle"></i>
                <div>Collecte en cours</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('collecteur.collecte.terminees') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-check-circle"></i>
                <div>Collectes terminées</div>
            </a>
        </li>

      

        <li class="menu-item">
            <a href="{{ route('collecteur.historique') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-history"></i>
                <div>Mes activités</div>
            </a>
        </li>

        <!-- ZONE -->
        <li class="menu-header small text-uppercase">
            <span>Zone</span>
        </li>

        <li class="menu-item">
            <a href="{{ route('collecteur.zone') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-map"></i>
                <div>Ma zone</div>
            </a>
        </li>

    </ul>
</aside>
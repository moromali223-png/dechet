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

<!-- resources/views/partials/sidebar-collecteur.blade.php -->
<ul class="menu-inner py-1">

    <li class="menu-item">
        <a href="{{ route('dashboard.collecteur') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-tachometer"></i>
            <div>Tableau de bord</div>
        </a>
    </li>

    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-calendar-check"></i>
            <div>Mes Missions</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar"></i>
                    <div>Planning</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-truck"></i>
                    <div>Collectes en cours</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-history"></i>
                    <div>Historique</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-truck"></i>
            <div>Collecte Terrain</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-play-circle"></i>
                    <div>Démarrer collecte</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-check-circle"></i>
                    <div>Mes collectes terminées</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a href="" class="menu-link">
            <i class="menu-icon tf-icons bx bx-map"></i>
            <div>Ma Zone</div>
        </a>
    </li>

    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div>Mon Compte</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{ route('profile.edit') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-circle"></i>
                    <div>Mon profil</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="#" class="menu-link" onclick="event.preventDefault(); this.closest('form').submit();">
                <i class="menu-icon tf-icons bx bx-power-off"></i>
                <div>Déconnexion</div>
            </a>
        </form>
    </li>

</ul>
</aside>
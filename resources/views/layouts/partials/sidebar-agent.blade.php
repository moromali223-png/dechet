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

    <!-- resources/views/partials/sidebar-agent.blade.php -->
<ul class="menu-inner py-1">

    <li class="menu-item">
        <a href="{{ route('agent.dashboard') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-tachometer"></i>
            <div>Tableau de bord</div>
        </a>
    </li>

    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-package"></i>
            <div>Collectes & Pesage</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{ route('agent.collectes.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-inbox"></i>
                    <div>Collectes reçues</div>
                </a>
            </li>
          <li class="menu-item">
            <a href="{{ route('agent.pesages.index') }}" class="menu-link">
                <i class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3L2 9l10 6 10-6-10-6zm0 13l-10-6v6l10 6 10-6v-6l-10 6z"/>
                    </svg>
                </i>
                <div>Pesage</div>
            </a>
          </li>
        </ul>
    </li>

    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-filter-alt"></i>
            <div>Tri & Production</div>
        </a>
        <ul class="menu-sub">
             <li class="menu-item">
                    <a href="{{ route('agent.tries.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-filter-alt"></i>
                        <div>Tri des déchets</div>
                    </a>
                </li>
             <li class="menu-item">
                    <a href="{{ route('agent.matieres.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-recycle"></i>
                        <div>Matières premières</div>
                    </a>
                </li>
             <li class="menu-item">
                    <a href="{{ route('agent.produits.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-box"></i>
                        <div>Produits</div>
                    </a>
                </li>
        </ul>
    </li>

    <li class="menu-item">
        <a href="{{ route('agent.stocks.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-archive"></i>
            <div>Stock produits finis</div>
        </a>
    </li>

    <li class="menu-item">
        <a href="{{ route('agent.rapports.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
            <div>Rapports</div>
        </a>
    </li>

</ul>
</aside>
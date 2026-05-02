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
        <a href="{{ route('dashboard.agent') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-tachometer"></i>
            <div>Tableau de bord</div>
        </a>
    </li>

    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-package"></i>
            <div>Réception & Pesage</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-inbox"></i>
                    <div>Réceptions</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-scale"></i>
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
                <a href="" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-filter-alt"></i>
                    <div>Tri des déchets</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-factory"></i>
                    <div>Production</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a href="" class="menu-link">
            <i class="menu-icon tf-icons bx bx-box"></i>
            <div>Stock</div>
        </a>
    </li>

    <li class="menu-item">
        <a href="" class="menu-link">
            <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
            <div>Rapports</div>
        </a>
    </li>

</ul>
</aside>
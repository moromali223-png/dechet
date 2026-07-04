<!-- =======================================================
   SIDEBAR ADMINISTRATEUR ECOFLUX - VERSION PROFESSIONNELLE
======================================================== -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- Logo -->
    <div class="app-brand demo d-flex justify-content-center mb-3">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
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

    @php
        $isUserOpen = request()->routeIs('clients.*') || request()->routeIs('agents.*') || request()->routeIs('collecteurs.*');
        $isContractsOpen = request()->routeIs('abonnements.*') || request()->routeIs('admin.declarations.*');
        $isPlanificationOpen = request()->routeIs('planifications.*') || request()->routeIs('tournees.*') || request()->routeIs('affectations.*') || request()->routeIs('suivi_collecte.*') || request()->routeIs('zones.*');
        $isTraitementOpen = request()->routeIs('pesages.*') || request()->routeIs('tries.*');
        $isStockOpen = request()->routeIs('admin.produits.*') || request()->routeIs('inventaire.*') || request()->routeIs('stock-entree.*') || request()->routeIs('mouvements.*') || request()->routeIs('alertes.*');
        $isVentesOpen = request()->routeIs('admin.commandes.*') || request()->routeIs('admin.paiements.*');
    @endphp

    <ul class="menu-inner py-1">

        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-tachometer"></i>
                <div>Tableau de bord</div>
            </a>
        </li>

         <!-- ==================== GESTION DES UTILISATEURS ==================== -->
        <li class="menu-item {{ $isUserOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div>Gestion des utilisateurs</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                    <a href="{{ route('clients.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-user"></i>
                        <div>Clients</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('agents.*') ? 'active' : '' }}">
                    <a href="{{ route('agents.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-shield"></i>
                        <div>Agents</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('collecteurs.*') ? 'active' : '' }}">
                    <a href="{{ route('collecteurs.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-car"></i>
                        <div>Collecteurs</div>
                    </a>
                </li>
            </ul>
        </li>

          <!-- ==================== CONTRATS & DÉCLARATIONS ==================== -->
        <li class="menu-item {{ $isContractsOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx bx-edit"></i>
                <div>Contrats & Déclarations</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('abonnements.*') ? 'active' : '' }}">
                    <a href="{{ route('abonnements.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-receipt"></i>
                        <div>Abonnements</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.declarations.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.declarations.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-file"></i>
                        <div>Déclarations</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Operations</span>
        </li>

        <!-- ==================== COLLECTE & PLANIFICATION ==================== -->
        <li class="menu-item {{ $isPlanificationOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-recycle"></i>
                <div>Collecte & Planification</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('planifications.*') ? 'active' : '' }}">
                    <a href="{{ route('planifications.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                        <div>Planifications</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('tournees.*') ? 'active' : '' }}">
                    <a href="{{ route('tournees.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-trip"></i>
                        <div>Tournées du jour</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('affectations.*') ? 'active' : '' }}">
                    <a href="{{ route('affectations.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-user-check"></i>
                        <div>Affectations</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('suivi_collecte.*') ? 'active' : '' }}">
                    <a href="{{ route('suivi_collecte.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-show-alt"></i>
                        <div>Suivi des collectes</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('zones.*') ? 'active' : '' }}">
                    <a href="{{ route('zones.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-map"></i>
                        <div>Zones</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- ==================== TRAITEMENT ==================== -->
        <li class="menu-item {{ $isTraitementOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layer"></i>
                <div>Traitement</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('pesages.*') ? 'active' : '' }}">
                    <a href="{{ route('pesages.index') }}" class="menu-link">
                        <i class="menu-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"> <path d="M12 3L2 9l10 6 10-6-10-6zm0 13l-10-6v6l10 6 10-6v-6l-10 6z"/> </svg> </i>
                        <div>Pesage</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('tries.*') ? 'active' : '' }}">
                    <a href="{{ route('tries.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-filter-alt"></i>
                        <div>Tri des déchets</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- ==================== GESTION DU STOCK ==================== -->
        <li class="menu-item {{ $isStockOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div>Gestion du stock</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.produits.*') ? 'active' : '' }}">
                    <a href="{{ route('produits.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-box"></i>
                        <div>Produits</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('inventaire.*') ? 'active' : '' }}">
                    <a href="{{ route('inventaire.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-list-check"></i>
                        <div>Inventaire</div>
                    </a>
                </li>
                <!-- <li class="menu-item {{ request()->routeIs('stock-entree.*') ? 'active' : '' }}">
                    <a href="{{ route('stock-entree.create') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-plus-circle"></i>
                        <div>Entrée en stock</div>
                    </a>
                </li> -->
                <li class="menu-item {{ request()->routeIs('mouvements.*') ? 'active' : '' }}">
                    <a href="{{ route('mouvements.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-transfer"></i>
                        <div>Mouvements</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('alertes.*') ? 'active' : '' }}">
                    <a href="{{ route('alertes.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-bell"></i>
                        <div>Alertes</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- ==================== VENTES & PAIEMENTS ==================== -->
        <li class="menu-item {{ $isVentesOpen ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                <div>Ventes & Paiements</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.commandes.*') ? 'active' : '' }}">
                    <a href="{{ route('commandes.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-cart"></i>
                        <div>Commandes</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.paiements.*') ? 'active' : '' }}">
                    <a href="{{ route('paiements.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-credit-card"></i>
                        <div>Paiements</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- ==================== RAPPORTS ==================== -->
        <li class="menu-item {{ request()->routeIs('admin.rapports.*') ? 'active' : '' }}">
            <a href="{{ route('rapports.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div>Rapports & Statistiques</div>
            </a>
        </li>

        <!-- ==================== CONFIGURATION ==================== -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Configuration</span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.parametres.index') ? 'active' : '' }}">
            <a href="{{ route('parametres.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div>Paramètres système</div>
            </a>
        </li>

    </ul>
</aside>
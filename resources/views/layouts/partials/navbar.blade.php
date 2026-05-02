
      
        <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item search-container position-relative">
                  <div class="search-wrapper d-flex align-items-center bg-light rounded-pill px-3 py-2 shadow-sm">
                    <i class="bx bx-search fs-5 text-muted me-2 search-icon"></i>
                    <input
                      type="text"
                      class="search-input form-control border-0 shadow-none bg-transparent"
                      placeholder="Rechercher collecteurs, zones, produits..."
                      aria-label="Rechercher dans EcoFlux"
                      autocomplete="off"
                    />
                    <div class="search-shortcut bg-secondary text-white rounded-pill px-2 py-1 ms-2 d-none d-md-block">
                      <small class="fw-bold">Ctrl+K</small>
                    </div>
                  </div>

                  <!-- Search Results Dropdown -->
                  <div class="search-results position-absolute bg-white shadow-lg rounded-3 mt-2 d-none" style="width: 400px; max-width: 90vw; z-index: 1050;">
                    <div class="search-results-header px-3 py-2 border-bottom">
                      <small class="text-muted fw-semibold">Résultats de recherche</small>
                    </div>
                    <div class="search-results-body" style="max-height: 300px; overflow-y: auto;">
                      <!-- Quick Actions -->
                      <div class="quick-actions px-3 py-2">
                        <small class="text-muted d-block mb-2">Actions rapides</small>
                        <div class="d-flex flex-column gap-1">
                          <a href="#" class="search-result-item d-flex align-items-center p-2 rounded-2 text-decoration-none">
                            <div class="result-icon bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                              <i class="bx bx-plus text-primary"></i>
                            </div>
                            <div>
                              <div class="fw-semibold text-dark">Nouvelle collecte</div>
                              <small class="text-muted">Créer une opération de collecte</small>
                            </div>
                          </a>
                          <a href="#" class="search-result-item d-flex align-items-center p-2 rounded-2 text-decoration-none">
                            <div class="result-icon bg-success bg-opacity-10 rounded-circle p-2 me-3">
                              <i class="bx bx-truck text-success"></i>
                            </div>
                            <div>
                              <div class="fw-semibold text-dark">État collecteurs</div>
                              <small class="text-muted">Voir les véhicules actifs</small>
                            </div>
                          </a>
                          <a href="#" class="search-result-item d-flex align-items-center p-2 rounded-2 text-decoration-none">
                            <div class="result-icon bg-info bg-opacity-10 rounded-circle p-2 me-3">
                              <i class="bx bx-package text-info"></i>
                            </div>
                            <div>
                              <div class="fw-semibold text-dark">Contrôle stock</div>
                              <small class="text-muted">Vérifier l'inventaire</small>
                            </div>
                          </a>
                        </div>
                      </div>

                      <!-- Recent Searches -->
                      <div class="recent-searches px-3 py-2 border-top">
                        <small class="text-muted d-block mb-2">Recherches récentes</small>
                        <div class="d-flex flex-wrap gap-1">
                          <span class="badge bg-light text-dark rounded-pill px-3 py-1 cursor-pointer">Zone A</span>
                          <span class="badge bg-light text-dark rounded-pill px-3 py-1 cursor-pointer">Collecteur #12</span>
                          <span class="badge bg-light text-dark rounded-pill px-3 py-1 cursor-pointer">Déchets organiques</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->
                <li class="nav-item lh-1 me-3">
                  <a
                    class="github-button"
            
                    data-icon="octicon-star"
                    data-size="large"
                    data-show-count="true"
                    aria-label="Star themeselection/sneat-html-admin-template-free on GitHub"
                    >Star</a
                  >
                </li>

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block">John Doe</span>
                            <small class="text-muted">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                          <span class="flex-grow-1 align-middle">Billing</span>
                          <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                          <i class="bx bx-power-off me-2"></i>
                          <span class="align-middle">Log Out</span>
                        </button>
                      </form>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

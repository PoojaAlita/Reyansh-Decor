  <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
          <div class="container-xxl">
            <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
              <a href="{{ route('dashboard') }}" class="app-brand-link gap-2">
                <span class="app-brand-logo">
                     <img src="{{ asset('image/logo/logo.png') }}" alt="Full Logo" class="full-logo" height="50"
                    id="full-logo">
                </span>
              </a>

              <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                <i class="bx bx-x bx-sm align-middle"></i>
              </a>
            </div>

            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <ul class="navbar-nav flex-row align-items-center ms-auto">

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ auth()->user()->image ? asset(auth()->user()->image) : asset('assets/img/avatars/12.png') }}" 
                            alt="User Image" class="rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="pages-account-settings-account.html">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="{{ auth()->user()->image ? asset(auth()->user()->image) : asset('assets/img/avatars/12.png') }}" 
                              alt="User Image" class="rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block lh-1">{{auth()->user()->name}}</span>
                            <small>{{auth()->user()->type == 'U' ? 'User' : 'Admin'}}</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <hr/>
                      <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                          <i class="bx bx-power-off me-2"></i>
                          <span class="align-middle">Log Out</span>
                        </a>
                      </li>
                </li>
                <!--/ User -->
              </ul>
            </div>

            <!-- Search Small Screens -->
            <div class="navbar-search-wrapper search-input-wrapper container-xxl d-none">
              <input
                type="text"
                class="form-control search-input border-0"
                placeholder="Search..."
                aria-label="Search..." />
              <i class="bx bx-x bx-sm search-toggler cursor-pointer"></i>
            </div>
          </div>
        </nav>


 <aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
              <div class="container-xxl d-flex h-100">

                <ul class="menu-inner" id="sidebarMenu">

                  <!-- Dashboard (example) -->
                  <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                      <a href="{{ route('dashboard') }}" class="menu-link">
                          <i class="menu-icon tf-icons bx bx-home-circle"></i>
                          <div>Dashboard</div>
                      </a>
                  </li>

                  

                  @php
                      use Illuminate\Support\Str;
                  @endphp

                  @foreach($menuData->where('parent_id', 0)->where('isshown', 1)->sortBy('sortorder') as $menu)
                      @php
                          $children = $menuData->where('parent_id', $menu->id)->where('isshown', 1)->sortBy('sortorder');
                          $hasChildren = $children->count() > 0;

                          // Detect active route (for highlight + open)
                          $isActive = request()->is(trim($menu->url, '/')) ||
                                      $children->contains(function($child) {
                                          return request()->is(trim($child->url, '/'));
                                      });   
                      @endphp

                      <li class="menu-item {{ $isActive ? 'active' : '' }}">
                          <a href="{{ $hasChildren ? 'javascript:void(0);' : url($menu->url) }}"
                            class="menu-link d-flex align-items-center justify-content-between"
                            @if($hasChildren)
                                data-bs-toggle="collapse"
                                data-bs-target="#submenu-{{ $menu->id }}"
                                aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                            @endif>
                              <span>
                                  <i class="menu-icon tf-icons {{ $menu->icon ?? 'bx bx-folder' }}"></i>
                                  {{ ucfirst($menu->title) }}
                              </span>
                              @if($hasChildren)
                                  <i class="bx bx-chevron-down ms-auto"></i>
                              @endif
                          </a>

                          @if($hasChildren)
                              <ul class="collapse list-unstyled ps-4 {{ $isActive ? 'show' : '' }}"
                                  id="submenu-{{ $menu->id }}"
                                  data-bs-parent="#sidebarMenu">
                                  @foreach($children as $child)
                                      <li class="menu-item {{ request()->is(trim($child->url, '/')) ? 'active' : '' }}">
                                          <a href="{{ url($child->url) }}" class="menu-link">
                                              <i class="menu-icon tf-icons {{ $child->icon ?? 'bx bx-right-arrow-alt' }}"></i>
                                              {{ ucfirst($child->title) }}
                                          </a>
                                      </li>
                                  @endforeach
                              </ul>
                          @endif
                      </li>
                  @endforeach
              </ul>


              </div>
            </aside>
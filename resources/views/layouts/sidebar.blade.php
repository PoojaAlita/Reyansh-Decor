 <aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
              <div class="container-xxl d-flex h-100">
                <ul class="menu-inner">
                  <!-- Dashboards -->
                
                  <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div>Dashboard</div>
                        </a>
                    </li>
                      {{-- @include('partials.dynamic-menu') --}}
                        
  @foreach($menuData->where('parent_id',0)->where('isshown',1)->sortBy('sortorder') as $menu)

                    @php
                        $children = $menuData->where('parent_id',$menu->id)
                                            ->where('isshown',1)
                                            ->sortBy('sortorder');

                        $hasChildren = $children->count() > 0;

                        $isActive = isActiveUrl($menu->url)
                                    || $children->contains(fn($c)=> isActiveUrl($c->url));
                    @endphp

                  <li class="menu-item {{ $isActive ? 'active' : '' }}">
                    <a href="{{ $hasChildren ? 'javascript:void(0);' : ($menu->url ?? '#') }}" class="menu-link {{ $hasChildren ? 'menu-toggle' : '' }}">
                      <i class="menu-icon tf-icons {{ $menu->icon }}"></i>
                      <div data-i18n="{{ ucfirst($menu->title) }}">{{ ucfirst($menu->title) }}</div>
                    </a>
                 @if($hasChildren)
                    

                    <ul class="menu-sub">
                         @foreach($children as $child)

                                @php
                                    $grand = $menuData->where('parent_id',$child->id)
                                                      ->where('isshown',1)
                                                      ->sortBy('sortorder');
                                    $hasGrand = $grand->count() > 0;
                                    $activeChild = isActiveUrl($child->url)
                                                   || $grand->contains(fn($g)=> isActiveUrl($g->url));
                                @endphp
                      <li class="menu-item {{ $activeChild ? 'active' : '' }}">
                        <a href="{{ $hasGrand ? 'javascript:void(0)' : url($child->url) }}" class="menu-link {{ $hasGrand ? 'menu-toggle' : '' }}">
                          <i class="menu-icon tf-icons {{ $child->icon }}"></i>
                          <div data-i18n="{{ $child->title }}">{{ $child->title }}</div>
                        </a>

                        {{-- @if($hasGrand)
                                        <ul class="menu-sub">
                                            @foreach($grand as $g)
                                                <li class="menu-item {{ isActiveUrl($g->url) ? 'active' : '' }}">
                                                    <a href="{{ url($g->url) }}" class="menu-link">
                                                        <i class="menu-icon tf-icons {{ $g->icon }}"></i>
                                                        <div>{{ $g->title }}</div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif --}}
                      </li>

                     @endforeach
                    </ul>
                 @endif
  
                  </li>
            @endforeach
            
                </ul>
              </div>
            </aside>
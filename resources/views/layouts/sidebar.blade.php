<aside id="layout-menu"
    class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">

    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner">

            <!-- Dashboard -->
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            <!-- Dynamic Menu -->
            @foreach($menuTree as $menu)

                @php
                    // Check active at any depth
                    $isActive =
                        isActiveUrl($menu->url) ||
                        collect($menu->children)->contains(fn($c) => isActiveUrl($c->url)) ||
                        collect($menu->children)->flatMap(fn($c) => $c->children)
                            ->contains(fn($sc) => isActiveUrl($sc->url));
                @endphp

                <li class="menu-item {{ $isActive ? 'active' : '' }}">

                    <a href="{{ count($menu->children) ? 'javascript:void(0)' : url($menu->url) }}"
                       class="menu-link {{ count($menu->children) ? 'menu-toggle' : '' }}">

                        <i class="menu-icon tf-icons {{ $menu->icon }}"></i>
                        <div>{{ ucfirst($menu->title) }}</div>
                    </a>

                    @if(count($menu->children))
                        <ul class="menu-sub">

                            @foreach($menu->children as $child)

                                @php
                                    $subActive =
                                        isActiveUrl($child->url) ||
                                        collect($child->children)->contains(fn($sc)=>isActiveUrl($sc->url));
                                @endphp

                                <li class="menu-item {{ $subActive ? 'active' : '' }}">

                                    <a href="{{ count($child->children) ? 'javascript:void(0)' : url($child->url) }}"
                                       class="menu-link {{ count($child->children) ? 'menu-toggle' : '' }}">
                                        <i class="menu-icon tf-icons {{ $child->icon }}"></i>
                                        <div>{{ ucfirst($child->title) }}</div>
                                    </a>

                                    @if(count($child->children))
                                        <ul class="menu-sub">
                                            @foreach($child->children as $sub)
                                                <li class="menu-item {{ isActiveUrl($sub->url) ? 'active' : '' }}">
                                                    <a href="{{ url($sub->url) }}" class="menu-link">
                                                        <i class="menu-icon tf-icons {{ $sub->icon }}"></i>
                                                        <div>{{ ucfirst($sub->title) }}</div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                </li>

                            @endforeach

                        </ul>
                    @endif

                </li>

            @endforeach

        </ul>
    </div>
</aside>

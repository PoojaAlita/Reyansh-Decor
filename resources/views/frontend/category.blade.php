@extends('layouts.user')

@section('content')
<!-- Shop Header -->
<section style="background: var(--light); padding: 80px 0; border-bottom: 1px solid #eee;">
    <div class="container">
        <div style="text-align: left;">
            <nav style="position: static; background: transparent; height: auto; border: none; padding: 0; backdrop-filter: none; margin-bottom: 15px;">
                <a href="{{ route('home') }}" style="color: var(--muted); text-decoration: none; font-size: 14px; font-weight: 500;">Home</a>
                <span style="color: #ccc; margin: 0 12px;">/</span>
                <span style="color: var(--primary); font-size: 14px; font-weight: 700;">{{ $category->name }}</span>
            </nav>
            <h1 style="font-size: 56px; margin: 0; font-weight: 900; line-height: 1;">{{ $category->name }}</h1>
        </div>
    </div>
</section>

<section class="container" style="padding: 80px 0; min-height: 80vh;">
    <div style="display: flex; gap: 60px;">
        
        <!-- Sidebar -->
        <aside style="flex: 0 0 300px; display: block; @media (max-width: 900px) { display: none; }">
            <div style="position: sticky; top: 110px;">
                <div style="margin-bottom: 50px;">
                    <h4 style="font-size: 18px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 25px; border-bottom: 2px solid var(--accent); display: inline-block;">Categories</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($frontendCategories as $cat)
                            <li style="margin-bottom: 15px;">
                                <a href="{{ route('frontend.category', $cat->id) }}" style="text-decoration: none; color: {{ $cat->id == $category->id ? 'var(--accent)' : 'var(--text)' }}; font-weight: {{ $cat->id == $category->id ? '700' : '500' }}; font-size: 16px; transition: all 0.3s; display: flex; align-items: center; justify-content: space-between;" onmouseover="this.style.paddingLeft='10px'" onmouseout="this.style.paddingLeft='0'">
                                    {{ $cat->name }}
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if(isset($subcategories) && $subcategories->count())
                <div style="margin-bottom: 50px;">
                    <h4 style="font-size: 18px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 25px; border-bottom: 2px solid var(--accent); display: inline-block;">Subcategories</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($subcategories as $sub)
                            <li style="margin-bottom: 11px; padding-left: 10px;">
                                <a href="{{ route('frontend.category', $category->id) }}?subcategory={{ $sub->id }}" style="text-decoration: none; color: {{ (isset($subcatId) && $subcatId == $sub->id) ? 'var(--accent)' : 'var(--text)' }}; font-weight: {{ (isset($subcatId) && $subcatId == $sub->id) ? '700' : '500' }}; font-size: 15px;">
                                    {{ $sub->subcat_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(isset($childcategories) && $childcategories->count())
                <div style="margin-bottom: 30px;">
                    <h4 style="font-size: 18px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 25px; border-bottom: 2px solid var(--accent); display: inline-block;">Child Categories</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($childcategories as $child)
                            <li style="margin-bottom: 11px; padding-left: 18px;">
                                <a href="{{ route('frontend.category', $category->id) }}?subcategory={{ $subcatId }}&childcategory={{ $child->id }}" style="text-decoration: none; color: {{ (isset($childcatId) && $childcatId == $child->id) ? 'var(--accent)' : 'var(--text)' }}; font-weight: {{ (isset($childcatId) && $childcatId == $child->id) ? '700' : '500' }}; font-size: 15px;">
                                    {{ $child->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div style="background: var(--primary); color: white; border-radius: 25px; padding: 40px; text-align: center; overflow: hidden; position: relative;">
                    <div style="position: relative; z-index: 1;">
                        <h4 style="font-size: 22px; margin-bottom: 15px;">Need Custom Decor?</h4>
                        <p style="font-size: 14px; opacity: 0.8; margin-bottom: 25px; line-height: 1.6;">Contact us for bespoke curtain sizes and matching fabrics for your home.</p>
                        <a href="#" style="background: var(--accent); color: white; padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 14px; display: inline-block;">Contact Us</a>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Product Listing -->
        <div style="flex: 1;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 45px; background: #fff; padding: 20px 0; border-bottom: 1px solid #f0f0f0;">
                <div style="font-size: 15px; color: var(--muted); font-weight: 500;">
                    Showing <span style="color: var(--primary); font-weight: 700;">{{ $products->count() }}</span> extraordinary pieces
                </div>
                <div style="display: flex; gap: 15px;">
                    <select style="border: 1px solid #eee; padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; outline: none; appearance: none; background: #fff;">
                        <option>Sort: Featured</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                    </select>
                </div>
            </div>

            @if($products->count() > 0)
                <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 40px;">
                    @foreach($products as $product)
                        @include('frontend.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
                
                <div style="margin-top: 80px;">
                    {{ $products->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 120px 0;">
                    <div style="font-size: 120px; color: #f0f0f0; margin-bottom: 40px;">
                        <svg width="150" height="150" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    </div>
                    <h3 style="font-size: 28px; font-weight: 800;">No Pieces Found</h3>
                    <p style="color: var(--muted); font-size: 18px; margin-bottom: 40px;">We're meticulously updating this collection. Check back soon for new arrivals.</p>
                    <a href="{{ route('home') }}" style="background: var(--primary); color: white; padding: 18px 50px; border-radius: 50px; text-decoration: none; font-weight: 700;">Discover More</a>
                </div>
            @endif
        </div>
    </div>
</section>

<style>
    .pagination { display: flex; list-style: none; padding: 0; gap: 10px; justify-content: center; }
    .page-item .page-link { padding: 10px 18px; border-radius: 10px; border: 1px solid #eee; text-decoration: none; color: var(--primary); font-weight: 700; transition: all 0.3s; }
    .page-item.active .page-link { background: var(--accent); border-color: var(--accent); color: white; }
    .page-item .page-link:hover:not(.active) { background: var(--light); }
</style>
@endsection

<div class="product-card">
    <a href="{{ route('frontend.product', $product->id) }}" class="product-card-link">
        <div class="product-image-container">
            <img src="{{ str_starts_with($product->main_image, 'http') ? $product->main_image : asset($product->main_image) }}" alt="{{ $product->name }}" class="product-image">
            @if($product->premium || ($product->sale_price && $product->sale_price < $product->price))
                <div class="product-badge">
                    @if($product->premium)
                        <span class="badge-premium">Premium</span>
                    @endif
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="badge-sale">Sale</span>
                    @endif
                </div>
            @endif
            <div class="product-action-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
            </div>
        </div>
        <div class="product-details">
            <span class="product-category">{{ $product->category->name ?? 'Collection' }}</span>
            <h3 class="product-title">{{ $product->name }}</h3>
            <div class="product-meta">
                <div class="product-pricing">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="price-original">₹{{ number_format($product->price, 0) }}</span>
                        <span class="price-current">₹{{ number_format($product->sale_price, 0) }}</span>
                    @else
                        <span class="price-current">₹{{ number_format($product->price, 0) }}</span>
                    @endif
                </div>
                <span class="product-action-link">View Details</span>
            </div>
        </div>
    </a>
</div>
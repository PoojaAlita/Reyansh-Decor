<a href="{{ route('frontend.category', $category->id) }}" class="category-card">
    <img src="{{ $category->image_url ?? 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $category->name }}" class="category-image">
    <div class="category-overlay">
        <div class="category-info">
            <h3 class="category-title">{{ $category->name }}</h3>
            <span class="category-cta">Explore Collection <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-left: 5px;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></span>
        </div>
    </div>
</a>
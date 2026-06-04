@extends('layouts.user')

@section('content')
<!-- Hero Slider -->
<section class="hero swiper heroSwiper" style="height: 100vh; min-height: 600px; position: relative; overflow: hidden;">
    <div class="swiper-wrapper">
        @foreach($sliders as $slider)
        <div class="swiper-slide hero-slide" style="background-image: url('{{ str_starts_with($slider->image, 'http') ? $slider->image : asset('uploads/sliders/' . $slider->image) }}');">
            <div class="hero-overlay"></div>
            <div class="container hero-container">
                <div class="hero-content" data-swiper-parallax="-300">
                    <span class="hero-badge" data-swiper-parallax="-200">Reyansh Decor Exclusive</span>
                    <h1 class="hero-title" data-swiper-parallax="-400">{{ $slider->title }}</h1>
                    <p class="hero-subtitle" data-swiper-parallax="-250">{{ $slider->sub_title }}</p>
                    <div class="hero-buttons" data-swiper-parallax="-150">
                        <a href="{{ $slider->link ?: '#' }}" class="btn-luxury btn-luxury-solid">Discover Shop</a>
                        <a href="#about-section" class="btn-luxury btn-luxury-outline">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <!-- navigation arrows -->
    <div class="swiper-button-prev" style="color:white;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
    </div>
    <div class="swiper-button-next" style="color:white;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
    </div>
    <div class="swiper-pagination"></div>
</section>

<!-- Features Ribbon -->
<section class="features-ribbon">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
                </div>
                <div>
                    <h4 class="feature-title">Premium Quality</h4>
                    <p class="feature-desc">Curated premium fabrics & weaves.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <div>
                    <h4 class="feature-title">Bespoke Fitting</h4>
                    <p class="feature-desc">Custom size window drapes.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                </div>
                <div>
                    <h4 class="feature-title">Handcrafted Heritage</h4>
                    <p class="feature-desc">Fine finish, meticulous details.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                </div>
                <div>
                    <h4 class="feature-title">Doorstep Delivery</h4>
                    <p class="feature-desc">Safely delivered across India.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="section-padding" style="background: #fbfbfa;">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Hot Picks</span>
            <h2 class="section-title">Top Products</h2>
            <p class="section-subtitle">A curated selection of our finest drapes, bedsheets, and rugs.</p>
        </div>

        <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px;">
            @foreach($products as $product)
                @include('frontend.partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <div style="text-align:center; margin-top: 70px;">
            <a href="#" class="btn-luxury btn-luxury-dark">Shop All Products</a>
        </div>
    </div>
</section>

<!-- Shop by Category Section -->
<section class="section-padding" style="background: white;">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 70px; flex-wrap: wrap; gap: 20px;">
            <div class="section-title" style="text-align: left; margin: 0;">
                <span class="section-tag" style="margin-bottom: 10px;">Shop by Space</span>
                <h2 class="section-title" style="margin: 0;">Luxury Spaces</h2>
                <p class="section-subtitle" style="margin: 5px 0 0 0; text-align: left;">Bespoke interior solutions tailored for every room.</p>
            </div>
            <a href="#" style="color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 2px; font-size: 13px; text-decoration: none; border-bottom: 2px solid var(--accent); padding-bottom: 5px; transition: var(--transition);" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--primary)'">View All Categories</a>
        </div>
        
        <div class="category-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
            @php
                $catImages = [
                    'CURTAIN' => 'https://images.unsplash.com/photo-1544450558-d39b8bc42a26?auto=format&fit=crop&q=80&w=800',
                    'FLAT BEDSHEET' => 'https://images.unsplash.com/photo-1622397333309-305609439972?auto=format&fit=crop&q=80&w=800',
                    'FOOT MAT' => 'https://images.unsplash.com/photo-1615529328331-f8917597711f?auto=format&fit=crop&q=80&w=800',
                    'TABLE MAT' => 'https://images.unsplash.com/photo-1560185127-6a4305810e03?auto=format&fit=crop&q=80&w=800',
                    'APPLIANCE COVER' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&q=80&w=800',
                ];
            @endphp
            @foreach($categories as $category)
                @php
                    $category->image_url = $catImages[$category->name] ?? ($category->image ? asset('storage/'.$category->image) : null);
                @endphp
                @include('frontend.partials.category-card', ['category' => $category])
            @endforeach
        </div>
    </div>
</section>

<!-- Video Section Showcase -->
<section class="section-padding" style="background: #fbfbfa; overflow-x: hidden;">
    <div class="container">
        <div class="section-header" style="text-align: left; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px; margin-bottom: 60px;">
            <div>
                <span class="section-tag" style="margin-bottom: 10px;">Design in Motion</span>
                <h2 class="section-title" style="margin: 0;">Artistry Behind The Weave</h2>
                <p class="section-subtitle" style="margin: 5px 0 0 0; text-align: left;">Take a closer look at the craftsmanship and materials of Reyansh Decor.</p>
            </div>
            <div style="width: 100px; height: 1px; background: #ddd;"></div>
        </div>
        
        <div class="video-showcase-container">
            @foreach($videos as $video)
                <div class="video-card">
                    <div class="video-wrapper">
                        <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" 
                                src="{{ str_replace('watch?v=', 'embed/', $video->video_url) }}" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about-section" class="section-padding" style="background: white;">
    <div class="container">
        <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 80px;">
            <div style="flex: 1.2; min-width: 350px;">
                <span class="section-tag" style="margin-bottom: 20px;">Who We Are</span>
                <h2 class="section-title" style="margin-bottom: 30px; line-height: 1.2;">Premium Home Décor Crafted for Sophisticated Living</h2>
                <p style="font-size: 17px; line-height: 1.9; color: #666; margin-bottom: 40px;">With deep roots in fabric craftsmanship and an enduring passion for elegant interiors, Reyansh Decor brings you bespoke drapery, luxury bedding, and decorative home accessories. Our team curates every collection with meticulous attention to detail, ensuring that each piece not only fits your space beautifully but tells a story of luxury, craftsmanship, and absolute comfort.</p>
                
                <div style="display: flex; gap: 50px; margin-bottom: 50px;">
                    <div>
                        <div class="metric-value">5,000+</div>
                        <div class="metric-label">Happy Homes</div>
                    </div>
                    <div>
                        <div class="metric-value">500+</div>
                        <div class="metric-label">Curated Weaves</div>
                    </div>
                </div>
                <a href="#" class="btn-luxury btn-luxury-dark">Explore Our Story</a>
            </div>
            <div style="flex: 1; min-width: 350px;" class="about-visuals">
                <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&q=80&w=800" alt="About Reyansh Decor" class="about-img-1">
                <img src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=600" alt="Drape Detail" class="about-img-2">
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="section-padding" style="background: #fbfbfa;">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Client Reviews</span>
            <h2 class="section-title">What Our Customers Say</h2>
            <p class="section-subtitle">Real experiences shared by clients who have transformed their homes with us.</p>
        </div>
        <div class="testimonial-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px;">
            <div class="testimonial-card">
                <span class="quote-icon">“</span>
                <div class="rating-stars">
                    @for($i=0; $i<5; $i++)
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg>
                    @endfor
                </div>
                <p class="testimonial-quote">"The curtains we ordered from Reyansh Decor transformed our living room completely. The linen fabric has a beautiful drape and feels incredibly luxurious. Their service was outstanding!"</p>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="https://i.pravatar.cc/60?img=32" alt="Anjali S." style="border-radius: 50%; width: 55px; height: 55px; object-fit: cover;">
                    <div>
                        <strong style="font-size: 16px; color: var(--primary); display: block;">Anjali Sharma</strong>
                        <span style="font-size: 13px; color: var(--muted);">New Delhi</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <span class="quote-icon">“</span>
                <div class="rating-stars">
                    @for($i=0; $i<5; $i++)
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg>
                    @endfor
                </div>
                <p class="testimonial-quote">"Fantastic range and even better quality. The flat bedsheets and table mats feel durable and look beautiful. It's the centre of compliments at every dinner party we host."</p>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="https://i.pravatar.cc/60?img=47" alt="Rohit K." style="border-radius: 50%; width: 55px; height: 55px; object-fit: cover;">
                    <div>
                        <strong style="font-size: 16px; color: var(--primary); display: block;">Rohit Kapoor</strong>
                        <span style="font-size: 13px; color: var(--muted);">Mumbai</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <span class="quote-icon">“</span>
                <div class="rating-stars">
                    @for($i=0; $i<5; $i++)
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg>
                    @endfor
                </div>
                <p class="testimonial-quote">"From placing the custom order to door delivery, everything was seamless. The fabrics have a premium thickness and block light exactly as promised. Extremely satisfied!"</p>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="https://i.pravatar.cc/60?img=58" alt="Meera P." style="border-radius: 50%; width: 55px; height: 55px; object-fit: cover;">
                    <div>
                        <strong style="font-size: 16px; color: var(--primary); display: block;">Meera Patel</strong>
                        <span style="font-size: 13px; color: var(--muted);">Bengaluru</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter CTA -->
<section class="section-padding" style="background: white;">
    <div class="container">
        <div class="newsletter-section">
            <div class="newsletter-bg-pattern"></div>
            <div class="newsletter-content-grid">
                <div>
                    <h2 style="font-size: clamp(28px, 4vw, 42px); font-family: 'Playfair Display', serif; margin: 0 0 15px 0; font-weight: 700;">Elevate Your Surroundings</h2>
                    <p style="font-size: 16px; opacity: 0.8; margin: 0; line-height: 1.6;">Subscribe to our newsletter to receive styling guides, lookbooks, and early access to new collection drops.</p>
                </div>
                <div>
                    <form action="#" method="POST" class="newsletter-input-group" onsubmit="event.preventDefault(); Swal.fire('Subscribed!', 'Thank you for subscribing.', 'success');">
                        <input type="email" placeholder="Your Email Address" required class="newsletter-input">
                        <button type="submit" class="newsletter-btn">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Promotional Banner -->
@foreach($banners as $index => $banner)
<section style="background: {{ $index % 2 == 0 ? '#11110f' : '#fcfcfa' }}; color: {{ $index % 2 == 0 ? 'white' : 'var(--primary)' }}; padding: 120px 0; overflow: hidden; border-bottom: 1px solid rgba(0,0,0,0.03);">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 80px; flex-wrap: wrap; flex-direction: {{ $index % 2 == 0 ? 'row' : 'row-reverse' }};">
            <div style="flex: 1.2; min-width: 350px;">
                <span class="section-tag" style="margin-bottom: 25px;">Limited Time Offer</span>
                <h2 style="font-size: clamp(32px, 5vw, 60px); margin: 0 0 25px 0; line-height: 1.1; font-weight: 900; font-family: 'Playfair Display', serif;">{{ $banner->title }}</h2>
                <p style="font-size: 18px; line-height: 1.8; opacity: 0.8; margin-bottom: 40px;">Elevate your home aesthetics with our premium collections. Crafted meticulously with heavy fabrics and modern designs to offer comfort and styling. Order today to avail seasonal discounts.</p>
                <a href="{{ $banner->link ?: '#' }}" class="btn-luxury {{ $index % 2 == 0 ? 'btn-luxury-outline' : 'btn-luxury-dark' }}">Shop Collection</a>
            </div>
            <div style="flex: 1; min-width: 350px; position: relative;">
                <div style="position: absolute; inset: -30px; border: 15px solid var(--accent); opacity: 0.15; border-radius: 20px; z-index: 0;"></div>
                <img src="{{ str_starts_with($banner->image, 'http') ? $banner->image : asset('storage/' . $banner->image) }}" style="width: 100%; border-radius: 20px; box-shadow: 0 30px 60px rgba(0,0,0,0.2); position: relative; z-index: 1;" alt="{{ $banner->title }}">
            </div>
        </div>
    </div>
</section>
@endforeach

<!-- Back to top button -->
<a href="#" id="backToTop" style="position: fixed; bottom: 40px; right: 40px; width: 50px; height: 50px; background: var(--accent); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; text-decoration: none; opacity: 0; pointer-events: none; transition: opacity 0.3s; z-index: 999; box-shadow: 0 10px 25px rgba(245, 48, 3, 0.35);">↑</a>

@include('frontend.home_styles')

@endsection

@section('scripts')
<script>
    const heroSwiper = new Swiper('.heroSwiper', {
        loop: true,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        speed: 1200,
        autoplay: { delay: 7000, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        parallax: true,
    });
    // back-to-top logic
    const backBtn = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backBtn.style.opacity = '1';
            backBtn.style.pointerEvents = 'auto';
        } else {
            backBtn.style.opacity = '0';
            backBtn.style.pointerEvents = 'none';
        }
    });
    backBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
@endsection

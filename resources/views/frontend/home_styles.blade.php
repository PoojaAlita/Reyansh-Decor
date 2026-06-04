<style>
    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInLeft {
        from { opacity: 0; transform: translateX(-40px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(40px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes pulseGlow {
        0% { box-shadow: 0 0 0 0 rgba(245, 48, 3, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(245, 48, 3, 0); }
        100% { box-shadow: 0 0 0 0 rgba(245, 48, 3, 0); }
    }

    /* Hero section */
    .hero-slide {
        background-size: cover !important;
        background-position: center !important;
        position: relative;
    }
    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.2) 100%);
        z-index: 1;
    }
    .hero-container {
        height: 100%;
        display: flex;
        align-items: center;
        position: relative;
        z-index: 2;
    }
    .hero-content {
        max-width: 800px;
        color: white;
    }
    .hero-badge {
        color: var(--accent-gold);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 8px;
        font-size: 13px;
        margin-bottom: 25px;
        display: block;
    }
    .hero-title {
        font-size: clamp(48px, 7.5vw, 90px);
        line-height: 1.1;
        margin-bottom: 25px;
        font-weight: 900;
        color: white;
        text-shadow: 0 4px 15px rgba(0,0,0,0.3);
        font-family: 'Playfair Display', serif;
    }
    .hero-subtitle {
        font-size: clamp(18px, 2.5vw, 24px);
        margin-bottom: 50px;
        font-weight: 300;
        color: rgba(255,255,255,0.95);
        line-height: 1.6;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        max-width: 600px;
    }
    .hero-buttons {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    /* Buttons */
    .btn-luxury {
        padding: 18px 45px;
        border-radius: 50px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 13px;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-luxury-solid {
        background: var(--accent);
        color: white;
        box-shadow: 0 15px 35px rgba(245, 48, 3, 0.3);
        border: 2px solid transparent;
    }
    .btn-luxury-solid:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(245, 48, 3, 0.45);
        background: #e32b00;
    }
    .btn-luxury-outline {
        background: transparent;
        color: white;
        border: 2px solid white;
    }
    .btn-luxury-outline:hover {
        background: white;
        color: var(--primary);
        transform: translateY(-3px);
    }
    .btn-luxury-dark {
        background: var(--primary);
        color: white;
        border: 2px solid transparent;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .btn-luxury-dark:hover {
        background: var(--accent);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(245, 48, 3, 0.3);
    }

    /* Ribbon Feature Section */
    .features-ribbon {
        background: white;
        padding: 40px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        position: relative;
        z-index: 10;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }
    .feature-item {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .feature-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--light);
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: var(--transition);
    }
    .feature-item:hover .feature-icon-wrapper {
        background: var(--primary);
        color: white;
        transform: rotateY(180deg);
    }
    .feature-title {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 5px 0;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .feature-desc {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    /* Generic Section Styles */
    .section-padding {
        padding: 120px 0;
    }
    .section-header {
        text-align: center;
        margin-bottom: 70px;
    }
    .section-tag {
        color: var(--accent-gold);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 4px;
        font-size: 13px;
        display: block;
        margin-bottom: 15px;
    }
    .section-title {
        font-size: clamp(32px, 5vw, 50px);
        font-weight: 900;
        margin: 0 0 15px 0;
        color: var(--primary);
        font-family: 'Playfair Display', serif;
    }
    .section-subtitle {
        color: var(--muted);
        font-size: clamp(16px, 2vw, 18px);
        max-width: 600px;
        margin: 0 auto;
    }

    /* Swiper Navigation Custom styling */
    .swiper-button-prev, .swiper-button-next {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.15);
        border-radius: 50%;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.3);
        transition: var(--transition) !important;
    }
    .swiper-button-prev:hover, .swiper-button-next:hover {
        background: rgba(255,255,255,0.35);
        border-color: rgba(255,255,255,0.6);
        transform: translateY(-50%) scale(1.08);
    }
    .swiper-pagination-bullet {
        background: rgba(255,255,255,0.5);
        opacity: 1;
        width: 12px;
        height: 12px;
        transition: var(--transition);
    }
    .swiper-pagination-bullet-active {
        background: white;
        width: 32px;
        border-radius: 10px;
    }

    /* Video section styling */
    .video-showcase-container {
        display: flex;
        gap: 30px;
        overflow-x: auto;
        padding-bottom: 40px;
        scrollbar-width: none;
    }
    .video-showcase-container::-webkit-scrollbar {
        display: none;
    }
    .video-card {
        flex: 0 0 750px;
        max-width: 90vw;
    }
    .video-wrapper {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 24px;
        box-shadow: 0 20px 45px rgba(0,0,0,0.12);
        background: #000;
        transition: var(--transition);
    }
    .video-wrapper:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.2);
    }

    /* About Section Overlapping Layout */
    .about-visuals {
        position: relative;
        height: 550px;
    }
    .about-img-1 {
        width: 75%;
        height: 420px;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
        transition: var(--transition);
    }
    .about-img-2 {
        width: 60%;
        height: 320px;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        position: absolute;
        bottom: 0;
        right: 0;
        z-index: 2;
        border: 8px solid white;
        transition: var(--transition);
    }
    .about-visuals:hover .about-img-1 {
        transform: translate(-10px, -10px);
    }
    .about-visuals:hover .about-img-2 {
        transform: translate(10px, 10px);
    }
    .metric-value {
        font-size: clamp(32px, 4vw, 42px);
        font-weight: 800;
        color: var(--accent);
        margin-bottom: 5px;
        font-family: 'Playfair Display', serif;
    }
    .metric-label {
        font-size: 12px;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 600;
    }

    /* Testimonials */
    .testimonial-card {
        background: white;
        padding: 45px;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0,0,0,0.02);
        position: relative;
        transition: var(--transition);
    }
    .testimonial-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--card-hover-shadow);
        border-color: rgba(var(--accent-rgb), 0.1);
    }
    .testimonial-quote {
        font-size: 17px;
        line-height: 1.7;
        color: #555;
        font-style: italic;
        margin: 0 0 30px 0;
    }
    .quote-icon {
        position: absolute;
        top: 30px;
        right: 40px;
        font-size: 80px;
        color: rgba(0,0,0,0.03);
        line-height: 1;
        font-family: 'Playfair Display', serif;
        pointer-events: none;
    }
    .rating-stars {
        color: var(--accent-gold);
        display: flex;
        gap: 4px;
        margin-bottom: 20px;
    }

    /* Newsletter Split Block */
    .newsletter-section {
        background: #11110f;
        border-radius: 30px;
        padding: 80px 60px;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: left;
    }
    .newsletter-bg-pattern {
        position: absolute;
        inset: 0;
        opacity: 0.05;
        background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
        background-size: 20px 20px;
        pointer-events: none;
    }
    .newsletter-content-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 60px;
        align-items: center;
    }
    @media (max-width: 991px) {
        .newsletter-content-grid {
            grid-template-columns: 1fr;
            gap: 40px;
            text-align: center;
        }
        .newsletter-section {
            padding: 60px 30px;
        }
    }
    .newsletter-input-group {
        display: flex;
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 50px;
        padding: 8px 8px 8px 25px;
        width: 100%;
        transition: var(--transition);
    }
    .newsletter-input-group:focus-within {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(var(--accent-rgb), 0.5);
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    }
    .newsletter-input {
        background: transparent;
        border: none;
        color: white;
        outline: none;
        flex: 1;
        font-family: inherit;
        font-size: 16px;
    }
    .newsletter-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    .newsletter-btn {
        background: white;
        color: var(--primary);
        border: none;
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 13px;
        cursor: pointer;
        transition: var(--transition);
    }
    .newsletter-btn:hover {
        background: var(--accent);
        color: white;
        transform: translateY(-2px);
    }
</style>

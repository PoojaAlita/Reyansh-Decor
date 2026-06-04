<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reyansh Decor | Premium Curtains & Drapery</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Third Party CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #1b1b18;
            --accent: #15338d;
            --bg: #FDFDFC;
            --text: #1b1b18;
            --muted: #706f6c;
            --light: #f5f5f5;
            --glass: rgba(255, 255, 255, 0.9);
            --accent-rgb: 21, 51, 141;
            --accent-gold: #c5a35c;
            --accent-red: #15338d;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            --card-hover-shadow: 0 25px 50px rgba(0, 0, 0, 0.08);
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, .logo { font-family: 'Playfair Display', serif; }

        .container { max-width: 1400px; margin: 0 auto; padding: 0 25px; }

        /* Navbar */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 90px;
            background: var(--glass);
            backdrop-filter: blur(15px);
            z-index: 1000;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s;
        }

        .nav-content { display: flex; justify-content: space-between; align-items: center; width: 100%; gap: 40px; }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            white-space: nowrap;
        }
        .logo img {
            height: 50px;
            display: block;
        }

        .nav-links { display: flex; gap: 35px; align-items: center; margin-left: auto; }
        .nav-links a {
            text-decoration: none;
            color: var(--text);
            font-weight: 600;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: color 0.3s;
            position: relative;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px; left: 0; width: 0; height: 2px;
            background: var(--accent);
            transition: width 0.3s;
        }
        .nav-links a:hover::after { width: 100%; }
        .nav-links a:hover { color: var(--accent); }

        /* Search Bar */
        .search-container {
            flex: 1;
            max-width: 500px;
            position: relative;
        }
        .search-bar {
            width: 100%;
            padding: 12px 25px 12px 50px;
            background: var(--light);
            border: 2px solid transparent;
            border-radius: 50px;
            outline: none;
            font-family: inherit;
            font-weight: 500;
            transition: all 0.3s;
        }
        .search-bar:focus {
            background: white;
            border-color: var(--accent);
            box-shadow: 0 5px 15px rgba(245, 48, 3, 0.1);
        }
        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
        }

        /* Dropdown Menu */
        .dropdown { position: relative; }
        .dropdown > a { display: inline-flex; align-items: center; gap: 6px; }
        .dropdown-content {
            position: absolute;
            top: 100%; left: 0;
            background: white;
            min-width: 250px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            border-radius: 15px;
            padding: 15px 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s;
            list-style: none;
        }
        .dropdown:hover .dropdown-content { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-content li a {
            padding: 12px 25px;
            display: block;
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            white-space: nowrap;
            text-transform: none;
            letter-spacing: 0;
            border: none;
        }
        .dropdown-content li a:hover { background: var(--light); color: var(--accent); }

        /* General UI */
        .badge {
            background: var(--accent);
            color: white;
            font-size: 10px;
            padding: 2px 7px;
            border-radius: 20px;
            vertical-align: middle;
            margin-left: 5px;
            font-weight: 800;
        }

        .cart-icon {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
            text-decoration: none;
            transition: color 0.3s;
        }
        .cart-badge {
            position: absolute;
            top: -10px; right: -12px;
            background: var(--accent);
            color: white;
            font-size: 11px;
            font-weight: 800;
            height: 20px; min-width: 20px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            padding: 0 4px;
            box-shadow: 0 4px 8px rgba(245, 48, 3, 0.3);
        }

        footer { background: var(--primary); color: white; padding: 100px 0 50px; }
        footer a { color: #aaa; text-decoration: none; transition: color 0.3s; }
        footer a:hover { color: white; }

        /* Responsive Improvements */
        @media (max-width: 1024px) {
            .nav-content {
                gap: 20px;
            }
            .logo {
                order: 1;
            }
            .nav-actions {
                order: 2;
                margin-left: auto;
            }
            .nav-toggle {
                display: flex;
                order: 3;
            }
            .nav-links {
                order: 4;
                position: absolute;
                top: 90px;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                align-items: stretch;
                gap: 0;
                padding: 15px 0;
                box-shadow: 0 15px 30px rgba(0,0,0,0.08);
                border-top: 1px solid rgba(0,0,0,0.05);
                transform: translateY(-120%);
                opacity: 0;
                pointer-events: none;
                transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
                z-index: 999;
            }
            .nav-links.show {
                transform: translateY(0);
                opacity: 1;
                pointer-events: auto;
            }
            .nav-links a {
                padding: 15px 30px;
                width: 100%;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0,0,0,0.02);
            }
            .nav-links a::after {
                display: none;
            }
            
            /* Mobile Dropdown */
            .dropdown {
                width: 100%;
            }
            .dropdown-content {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                background: var(--light);
                padding: 10px 0;
                display: none;
                border-radius: 0;
            }
            .dropdown.active .dropdown-content {
                display: block;
            }
            .dropdown-content li a {
                padding: 12px 45px;
            }
        }
        .nav-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            border: none;
            background: transparent;
            cursor: pointer;
            z-index: 1001;
            padding: 5px;
        }
        .nav-toggle span {
            width: 25px;
            height: 3px;
            background: var(--primary);
            display: block;
            transition: all 0.3s;
        }
        .nav-toggle.active span:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }
        .nav-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        .nav-toggle.active span:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }

        /* Product Cards */
        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            border: 1px solid rgba(0,0,0,0.03);
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--card-hover-shadow);
            border-color: rgba(var(--accent-rgb), 0.2);
        }
        .product-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .product-image-container {
            height: 380px;
            position: relative;
            overflow: hidden;
            background: var(--light);
        }
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        .product-card:hover .product-image {
            transform: scale(1.08);
        }
        .product-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 2;
        }
        .badge-premium {
            background: var(--primary);
            color: white;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .badge-sale {
            background: var(--accent-red);
            color: white;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .product-action-btn {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: white;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            color: var(--primary);
            transition: var(--transition);
            opacity: 0;
            transform: translateY(10px);
            z-index: 2;
        }
        .product-card:hover .product-action-btn {
            opacity: 1;
            transform: translateY(0);
        }
        .product-action-btn:hover {
            background: var(--primary);
            color: white;
        }
        .product-details {
            padding: 25px;
        }
        .product-category {
            color: var(--muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: block;
            margin-bottom: 8px;
        }
        .product-title {
            font-size: 18px;
            margin: 0 0 15px 0;
            font-weight: 600;
            height: 50px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            line-height: 1.4;
            color: var(--primary);
        }
        .product-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid var(--light);
            padding-top: 18px;
        }
        .product-pricing {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .price-original {
            font-size: 14px;
            color: var(--muted);
            text-decoration: line-through;
        }
        .price-current {
            font-size: 20px;
            color: var(--accent-red);
            font-weight: 700;
        }
        .product-action-link {
            color: var(--primary);
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            transition: var(--transition);
        }
        .product-action-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--primary);
            transition: var(--transition);
        }
        .product-card:hover .product-action-link::after {
            width: 100%;
        }

        /* Category Cards */
        .category-card {
            height: 420px;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            display: block;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            border: 1px solid rgba(0,0,0,0.02);
        }
        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-hover-shadow);
        }
        .category-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        .category-card:hover .category-image {
            transform: scale(1.08);
        }
        .category-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.3) 40%, transparent 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 35px;
            color: white;
            z-index: 1;
        }
        .category-info {
            transform: translateY(10px);
            transition: var(--transition);
        }
        .category-card:hover .category-info {
            transform: translateY(0);
        }
        .category-title {
            font-size: 26px;
            margin: 0 0 8px 0;
            font-weight: 700;
            color: white;
            font-family: 'Playfair Display', serif;
        }
        .category-cta {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.8;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }
        .category-card:hover .category-cta {
            opacity: 1;
            color: var(--accent-gold);
        }
        .category-cta svg {
            transition: var(--transition);
        }
        .category-card:hover .category-cta svg {
            transform: translateX(5px);
        }
    </style>
</head>
<body>
    <nav id="navbar">
        <div class="container nav-content">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('image/logo/logo.png') }}" alt="Reyansh Decor">
            </a>
            <button id="navToggle" class="nav-toggle" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="nav-links">
                <a href="{{ route('home') }}">Home</a>
                <div class="dropdown">
                    <a href="#">Shop Collections <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" style="margin-left: 2px;"><polyline points="6 9 12 15 18 9"></polyline></svg></a>
                    <ul class="dropdown-content">
                        @foreach($frontendCategories as $cat)
                            <li><a href="{{ route('frontend.category', $cat->id) }}">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <a href="#">About</a>
                <a href="#">Contact</a>
            </div>

            <div class="nav-actions" style="display: flex; align-items: center; gap: 25px;">
                <a href="{{ route('cart.index') }}" class="cart-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    <span class="cart-badge" id="cartBadgeCount">{{ $cartCount ?? 0 }}</span>
                </a>
                
                @auth
                    <a href="{{ url('/dashboard') }}" style="text-decoration: none; color: white; background: var(--primary); padding: 10px 22px; border-radius: 50px; font-weight: 600; font-size: 14px;">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" style="text-decoration: none; color: var(--text); font-weight: 700; font-size: 15px;">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <div style="margin-top: 90px;">
        @yield('content')
    </div>

    <footer>
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 80px; margin-bottom: 80px;">
                <div>
                    <div class="logo" style="color: white; margin-bottom: 30px;">REYANSH DECOR</div>
                    <p style="color: #888; line-height: 1.8; font-size: 16px;">Elevating spaces with premium drapes, designer curtains, and high-quality home mats. Redefining modern interiors since 2026.</p>
                </div>
                <div>
                    <h4 style="font-size: 18px; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 2px;">Shop</h4>
                    <ul style="list-style: none; padding: 0;">
                        @foreach($frontendCategories->take(5) as $cat)
                            <li style="margin-bottom: 12px;"><a href="{{ route('frontend.category', $cat->id) }}">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 style="font-size: 18px; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 2px;">Company</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 12px;"><a href="#">Our Story</a></li>
                        <li style="margin-bottom: 12px;"><a href="#">Terms & Conditions</a></li>
                        <li style="margin-bottom: 12px;"><a href="#">Privacy Policy</a></li>
                        <li style="margin-bottom: 12px;"><a href="#">Return Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-size: 18px; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 2px;">Newsletter</h4>
                    <p style="color: #888; font-size: 14px; margin-bottom: 20px;">Subscribe to get special offers and first look at new arrivals.</p>
                    <div style="display: flex; border-bottom: 1px solid #444; padding-bottom: 10px;">
                        <input type="email" placeholder="Email Address" style="background: transparent; border: none; color: white; flex: 1; outline: none;">
                        <button style="background: transparent; border: none; color: var(--accent); font-weight: 700; cursor: pointer;">JOIN</button>
                    </div>
                </div>
            </div>
            <div style="border-top: 1px solid #333; padding-top: 40px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 20px; color: #555; font-size: 14px;">
                <div>&copy; {{ date('Y') }} Reyansh Decor. Premium Interiors.</div>
                <div style="display: flex; gap: 30px;">
                    <a href="#">Instagram</a>
                    <a href="#">Facebook</a>
                    <a href="#">Twitter</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function updateCartCount(count) { $('#cartBadgeCount').text(count); }

        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.style.height = '75px';
                navbar.style.background = 'white';
                navbar.style.boxShadow = '0 10px 30px rgba(0,0,0,0.05)';
            } else {
                navbar.style.height = '90px';
                navbar.style.background = 'var(--glass)';
                navbar.style.boxShadow = 'none';
            }
        });

        // Mobile Menu Toggle
        const navToggle = document.getElementById('navToggle');
        const navLinks = document.querySelector('.nav-links');

        if (navToggle) {
            navToggle.addEventListener('click', function() {
                navLinks.classList.toggle('show');
                navToggle.classList.toggle('active');
            });
        }

        // Mobile Dropdown Toggle
        document.querySelectorAll('.dropdown > a').forEach(dropdownLink => {
            dropdownLink.addEventListener('click', function(e) {
                if (window.innerWidth <= 1024) {
                    e.preventDefault();
                    this.parentElement.classList.toggle('active');
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>

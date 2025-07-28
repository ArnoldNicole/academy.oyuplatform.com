@extends('layouts.home_page.master')

@section('content')
    <style>
        :root {
            --front-site-theme-color: {{ $settings['front_site_theme_color'] ?? '#e8fdf5' }};
            --primary-color: {{ $settings['primary_color'] ?? '#059669' }};
            --secondary-color: {{ $settings['secondary_color'] ?? '#1f2937' }};
            --accent-color: #34d399;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --preloader-img: url({{ $settings['horizontal_logo'] ?? asset('assets/home_page/img/Logo.svg') }});
            --gradient-primary: linear-gradient(135deg, #059669 0%, #34d399 100%);
            --gradient-secondary: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            --shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-large: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        /* Modern Reset & Base Styles */
        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            overflow-x: hidden;
        }

        /* Glassmorphism Header */
        #header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #header .logo img {
            max-height: 45px;
            transition: transform 0.3s ease;
        }

        #header .logo:hover img {
            transform: scale(1.05);
        }

        /* Modern Navigation */
        .navbar ul li a {
            color: var(--text-primary);
            font-weight: 500;
            font-size: 15px;
            padding: 12px 20px;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .navbar ul li a:hover,
        .navbar ul li a.active {
            color: var(--primary-color);
            background: rgba(5, 150, 105, 0.1);
            transform: translateY(-2px);
        }

        .navbar ul li a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-primary);
            transition: left 0.3s ease;
            z-index: -1;
            opacity: 0.1;
        }

        .navbar ul li a:hover::before {
            left: 0;
        }

        /* CTA Buttons */
        .login {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color) !important;
            padding: 10px 24px !important;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .login:hover {
            background: var(--primary-color);
            color: white !important;
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .register {
            background: var(--gradient-primary);
            color: white !important;
            padding: 10px 24px !important;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-soft);
        }

        .register:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-large);
        }

        /* Hero Section with Modern Layout */
        #hero {
            background: linear-gradient(135deg,
            rgba(248, 250, 252, 0.9) 0%,
            rgba(240, 253, 244, 0.9) 50%,
            rgba(236, 254, 255, 0.9) 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        #hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="%23059669" stop-opacity="0.1"/><stop offset="100%" stop-color="%23059669" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="150" fill="url(%23a)"/><circle cx="800" cy="300" r="200" fill="url(%23a)"/><circle cx="400" cy="700" r="100" fill="url(%23a)"/></svg>');
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .saas {
            display: inline-block;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            line-height: 1.1;
            color: var(--text-primary);
            margin-bottom: 24px;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-get-started {
            background: var(--gradient-primary);
            color: white;
            padding: 16px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-medium);
            position: relative;
            overflow: hidden;
        }

        .btn-get-started::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s ease;
        }

        .btn-get-started:hover::before {
            left: 100%;
        }

        .btn-get-started:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-large);
            color: white;
        }

        /* Floating Animation for Hero Images */
        .floating-elements img {
            position: absolute;
            animation: float 6s ease-in-out infinite;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.1));
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .book-img { top: 10%; left: 5%; animation-delay: 0s; }
        .calc-img { top: 20%; right: 10%; animation-delay: 1s; }
        .cap-img { bottom: 30%; left: 8%; animation-delay: 2s; }
        .glass-img { top: 50%; left: 2%; animation-delay: 3s; }
        .idea-img { top: 30%; right: 5%; animation-delay: 4s; }
        .rocket-img { bottom: 20%; right: 15%; animation-delay: 5s; }
        .scale-img { bottom: 10%; left: 15%; animation-delay: 6s; }

        /* Modern Hero Image */
        .hero-img img {
            border-radius: 24px;
            box-shadow: var(--shadow-large);
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hero-img:hover img {
            transform: scale(1.02) rotate(1deg);
        }

        /* Modern Features Section */
        .services {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 120px 0;
        }

        .section-title h2 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            color: var(--text-primary);
            text-align: center;
            margin-bottom: 20px;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .feature-div {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .feature-div::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-div:hover::before {
            transform: scaleX(1);
        }

        .feature-div:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-large);
            background: rgba(255, 255, 255, 0.95);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        /* Modern Pricing Section */
        .pricing {
            background: linear-gradient(135deg, var(--front-site-theme-color) 0%, #ffffff 100%);
            padding: 120px 0;
        }

        .pricing .box {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .pricing .box.featured {
            background: var(--gradient-primary);
            color: white;
            transform: scale(1.05);
            box-shadow: var(--shadow-large);
        }

        .pricing .box:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-large);
        }

        .pricing .box h2 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .pricing .box h4 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .pricing .box ul {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .pricing .box ul li {
            padding: 12px 0;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .pricing .box ul li i {
            margin-right: 12px;
            font-size: 18px;
        }

        .buy-btn {
            background: var(--gradient-primary);
            color: white;
            padding: 16px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-soft);
        }

        .buy-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            color: white;
        }

        .pricing .box.featured .buy-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .pricing .box.featured .buy-btn:hover {
            background: white;
            color: var(--primary-color);
        }

        /* Modern FAQ Section */
        .faq {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 120px 0;
        }

        .faq-list ul {
            list-style: none;
            padding: 0;
        }

        .faq-question {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            margin-bottom: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .faq-question:hover {
            box-shadow: var(--shadow-medium);
            transform: translateY(-2px);
        }

        .faq-question a {
            display: flex;
            align-items: center;
            padding: 24px;
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 18px;
        }

        .faq-question .icon-help {
            color: var(--primary-color);
            font-size: 24px;
            margin-right: 16px;
        }

        /* Modern Contact Section */
        .contact {
            background: var(--gradient-secondary);
            color: white;
            padding: 120px 0;
        }

        .contact .section-title h2 {
            color: white;
        }

        .php-email-form {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
        }

        .php-email-form .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            padding: 16px 20px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }

        .php-email-form .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .php-email-form .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.1);
            background: rgba(255, 255, 255, 0.15);
        }

        .php-email-form button {
            background: var(--gradient-primary);
            border: none;
            padding: 16px 32px;
            border-radius: 50px;
            color: white;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .php-email-form button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .info {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
        }

        .contact-img {
            width: 32px;
            height: 32px;
            filter: brightness(0) invert(1);
        }

        /* Modern Footer */
        .footer-1 {
            background: var(--gradient-primary);
            border-radius: 24px;
            margin: 80px auto;
            padding: 60px 40px;
            box-shadow: var(--shadow-large);
            position: relative;
            overflow: hidden;
        }

        .footer-1::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .mobile-footer {
            background: var(--gradient-primary);
            padding: 60px 20px;
            margin-top: 0;
        }

        .btn-apple, .btn-play {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 16px 24px;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .btn-apple:hover, .btn-play:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        #footer {
            background: var(--gradient-secondary);
            color: white;
        }

        .footer-top {
            padding: 80px 0 40px;
        }

        .footer-links ul {
            list-style: none;
            padding: 0;
        }

        .footer-links ul li {
            margin-bottom: 12px;
        }

        .footer-links ul li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links ul li a:hover {
            color: var(--accent-color);
        }

        .social-links a {
            display: inline-block;
            margin-right: 16px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .social-links a:hover {
            background: var(--accent-color);
            transform: translateY(-2px);
        }

        .social-links img {
            width: 24px;
            height: 24px;
            filter: brightness(0) invert(1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .title {
                font-size: 2.5rem;
            }

            .feature-div {
                margin-bottom: 30px;
            }

            .pricing .box {
                margin-bottom: 40px;
            }

            .footer-1 {
                margin: 40px 20px;
                padding: 40px 20px;
            }
        }

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Loading Animation */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .loading {
            animation: pulse 2s infinite;
        }

        /* Modern Dropdown */
        .navbar .dropdown:hover ul {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            box-shadow: var(--shadow-large);
        }

        .navbar .dropdown ul li a {
            color: var(--text-primary);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 8px;
        }

        .navbar .dropdown ul li a:hover {
            background: rgba(5, 150, 105, 0.1);
            color: var(--primary-color);
        }
    </style>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top autohide">
        <div class="container d-flex align-items-center">
            <h1 class="logo me-auto">
                <a href="{{ url('/') }}">
                    <img src="{{ $settings['horizontal_logo'] ?? asset('assets/home_page/img/Logo.svg') }}" alt="">
                </a>
            </h1>

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="#hero">{{ __('home') }}</a></li>
                    <li><a class="nav-link scrollto" href="#feature">{{ __('feature') }}</a></li>
                    <li><a class="nav-link scrollto" href="#pricing">{{ __('pricing') }}</a></li>
                    <li><a class="nav-link scrollto" href="#faq">{{ __('faq') }}</a></li>
                    <li><a class="nav-link scrollto" href="#contact">{{ __('contact') }}</a></li>

                    @if (count($guidances))
                        <li class="dropdown">
                            <a href="#"><span>{{ __('guidance') }}</span> <i class="bi bi-chevron-down"></i></a>
                            <ul>
                                @foreach ($guidances as $guidance)
                                    <li><a href="{{ $guidance->link }}">{{ $guidance->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endif

                    @if (count($languages))
                        <li class="dropdown">
                            <a href="#"><span>{{ __('language') }}</span> <i class="bi bi-chevron-down"></i></a>
                            <ul>
                                @foreach ($languages as $language)
                                    <li><a href="{{ url('set-language') . '/' . $language->code }}">{{ $language->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endif

                    @if (Auth::user())
                        <li><a class="login scrollto" href="{{ route('auth.logout') }}">{{ __('logout') }}</a></li>
                        <li><a class="register scrollto" href="/dashboard">{{ __('hello') }} {{ Auth::user()->first_name }}</a></li>
                    @else
                        <li><a class="login scrollto" href="{{ url('login') }}">{{ __('login') }}</a></li>
                        <li><a class="register" id="registration-form" href="javascript:void(0)">{{ __('register') }}</a></li>
                    @endif
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
        </div>
    </header>

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="floating-elements">
            <img src="{{ asset('assets/home_page/img/book.png') }}" class="book-img d-none d-md-block" alt="">
            <img src="{{ asset('assets/home_page/img/calc.png') }}" class="calc-img d-none d-md-block" alt="">
            <img src="{{ asset('assets/home_page/img/cap.png') }}" class="cap-img d-none d-md-block" alt="">
            <img src="{{ asset('assets/home_page/img/glass.png') }}" class="glass-img d-none d-md-block" alt="">
            <img src="{{ asset('assets/home_page/img/idea.svg') }}" class="idea-img d-none d-md-block" alt="">
            <img src="{{ asset('assets/home_page/img/rocket.svg') }}" class="rocket-img d-none d-md-block" alt="">
            <img src="{{ asset('assets/home_page/img/scale.svg') }}" class="scale-img d-none d-md-block" alt="">
        </div>

        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1" data-aos="fade-up" data-aos-delay="200">
                    <label class="saas">{{ $settings['system_name'] }}</label>
                    <h1 class="title">{{ $settings['tag_line'] ?? 'eSchool-Saas - Manage Your School' }}</h1>
                    <p class="hero-description" style="font-size: 18px; color: var(--text-secondary); margin-bottom: 32px; line-height: 1.6;">
                        Transform your educational institution with our comprehensive school management system. Streamline operations, enhance learning, and build stronger communities.
                    </p>
                    <div class="d-flex justify-content-center justify-content-lg-start gap-3">
                        <a href="#feature" class="btn-get-started scrollto">{{ __('get_started') }}</a>
                        <a href="#pricing" class="btn btn-outline-primary" style="padding: 16px 32px; border-radius: 50px; font-weight: 600; border: 2px solid var(--primary-color); color: var(--primary-color); text-decoration: none; transition: all 0.3s ease;">
                            View Pricing
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
                    <img src="{{ $settings['home_image'] ?? asset('assets/home_page/img/main_image-rbg.png') }}" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>
    </section>

    @include('register')

    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="feature" class="services section-bg">
            <div class="container" data-aos="fade-up">
                <div class="section-title">
                    <h2>{{ __('our_features') }}</h2>
                    <p style="font-size: 18px; color: var(--text-secondary); text-align: center; max-width: 600px; margin: 0 auto;">
                        Discover powerful tools designed to revolutionize education management and enhance learning experiences.
                    </p>
                </div>

                <div class="row g-4">
                    @foreach ($features as $feature)
                        <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="{{ 100 * $loop->iteration }}">
                            <div class="card h-100">
                                <div class="card-body feature-div">
                                    <div style="width: 60px; height: 60px; background: var(--gradient-primary); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                                        <i class="bi bi-check-circle" style="font-size: 28px; color: white;"></i>
                                    </div>
                                    <h4 class="card-title">{{ __($feature->name) }}</h4>
                                    <p style="color: var(--text-secondary); font-size: 14px; line-height: 1.5;">
                                        Streamline your school operations with this powerful feature.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        @if ($packages && false)
            <!-- ======= Pricing Section ======= -->
            <section id="pricing" class="pricing">
                <div class="container" data-aos="fade-up">
                    <div class="section-title">
                        <h2>{{ __('flexible_pricing_packages') }}</h2>
                        <p style="font-size: 18px; color: var(--text-secondary); text-align: center; max-width: 600px; margin: 0 auto;">
                            Choose the perfect plan for your institution. Scale as you grow with our flexible pricing options.
                        </p>
                    </div>

                    <div class="row justify-content-center g-4">
                        @php $delay = 0; @endphp
                        @foreach ($packages as $package)
                            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $delay += 100 }}">
                                <div class="box @if ($package->highlight) featured @endif">
                                    @if ($package->highlight)
                                        <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: rgba(255,255,255,0.2); color: white; padding: 8px 24px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                                            Most Popular
                                        </div>
                                    @endif

                                    <h2>{{ $package->name }}</h2>

                                    <div class="pricing-details" style="margin: 30px 0;">
                                        @if ($package->is_trial == 1)
                                            <div style="text-align: center; margin-bottom: 20px;">
                                                <div style="font-size: 32px; font-weight: 800; margin-bottom: 8px;">
                                                    {{ $systemSettings['trial_days'] }}
                                                </div>
                                                <div style="font-size: 16px; opacity: 0.8;">Days Trial</div>
                                            </div>
                                            <div style="background: rgba(255,255,255,0.1); border-radius: 12px; padding: 20px; margin: 20px 0;">
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                                    <span>Student Limit:</span>
                                                    <strong>{{ $settings['student_limit'] }}</strong>
                                                </div>
                                                <div style="display: flex; justify-content: space-between;">
                                                    <span>Staff Limit:</span>
                                                    <strong>{{ $settings['staff_limit'] }}</strong>
                                                </div>
                                            </div>
                                        @else
                                            <div style="text-align: center; margin-bottom: 20px;">
                                                <div style="font-size: 32px; font-weight: 800; margin-bottom: 8px;">
                                                    {{ $settings['billing_cycle_in_days'] }}
                                                </div>
                                                <div style="font-size: 16px; opacity: 0.8;">Days Billing Cycle</div>
                                            </div>
                                            <div style="background: rgba(255,255,255,0.1); border-radius: 12px; padding: 20px; margin: 20px 0;">
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                                    <span>Per Student:</span>
                                                    <strong>{{ $settings['currency_symbol'] }} {{ $package->student_charge }}</strong>
                                                </div>
                                                <div style="display: flex; justify-content: space-between;">
                                                    <span>Per Staff:</span>
                                                    <strong>{{ $settings['currency_symbol'] }} {{ $package->staff_charge }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <ul>
                                        @foreach ($features as $feature)
                                            @if (str_contains($package->package_feature->pluck('feature_id'), $feature->id))
                                                <li><i class="bx bx-check" style="color: #34d399;"></i>{{ __($feature->name) }}</li>
                                            @else
                                                <li class="na" style="opacity: 0.5;"><i class="bx bx-x" style="color: #ef4444;"></i><span>{{ __($feature->name) }}</span></li>
                                            @endif
                                        @endforeach
                                    </ul>

                                    <div style="margin-top: 40px;">
                                        <a href="{{ url('login') }}" class="buy-btn">{{ __('get_started') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- ======= FAQ Section ======= -->
        <section id="faq" class="faq section-bg">
            <div class="container" data-aos="fade-up">
                <div class="section-title">
                    <h2>{{ __('frequently_asked_questions') }}</h2>
                    <p style="font-size: 18px; color: var(--text-secondary); text-align: center; max-width: 600px; margin: 0 auto;">
                        Find answers to common questions about our school management system.
                    </p>
                </div>

                <div class="faq-list">
                    <ul>
                        @foreach ($faqs as $faq)
                            <li data-aos="fade-up" class="faq-question" data-aos-delay="{{ 100 * $loop->iteration }}">
                                <i class="bx bx-help-circle icon-help"></i>
                                <a data-bs-toggle="collapse" class="collapsed" data-bs-target="#faq-list-{{ $faq->id }}">
                                    {{ $faq->title }}
                                    <i class="bx bx-chevron-down icon-show" style="margin-left: auto; font-size: 20px; transition: transform 0.3s ease;"></i>
                                    <i class="bx bx-chevron-up icon-close" style="margin-left: auto; font-size: 20px; transition: transform 0.3s ease;"></i>
                                </a>
                                <div id="faq-list-{{ $faq->id }}" class="collapse" data-bs-parent=".faq-list">
                                    <div style="padding: 20px 70px; color: var(--text-secondary); line-height: 1.6;">
                                        {{ $faq->description }}
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        <!-- ======= Contact Section ======= -->
        <section id="contact" class="contact">
            <div class="container" data-aos="fade-up">
                <div class="section-title">
                    <h2>{{ __('lets_get_in_touch') }}</h2>
                    <p style="font-size: 18px; color: rgba(255,255,255,0.8); text-align: center; max-width: 600px; margin: 0 auto;">
                        Ready to transform your school? Get in touch with our team for personalized assistance.
                    </p>
                </div>

                <div class="row g-4">
                    <div class="col-lg-7">
                        <form action="{{ url('contact') }}" method="post" role="form" class="php-email-form create-form">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" name="title" class="form-control" placeholder="{{ __('name') }}" id="name" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" placeholder="{{ __('enter_email') }}" name="email" id="email" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <textarea class="form-control" name="message" placeholder="{{ __('message') }}" rows="8" required></textarea>
                            </div>
                            <div class="my-4">
                                <div class="loading" style="display: none;">{{ __('loading') }}</div>
                                <div class="error-message" style="color: #ef4444; display: none;"></div>
                                <div class="sent-message" style="color: var(--accent-color); display: none;">{{ __('Your message has been sent. Thank you') }}!</div>
                            </div>
                            <div class="text-center">
                                <button type="submit">{{ __('send_your_message') }}</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-5">
                        <div class="info">
                            <h3 style="color: white; margin-bottom: 30px; font-weight: 700;">{{ __('support_contact') }}</h3>

                            @if (isset($settings['mobile']))
                                <div class="contact-item" style="display: flex; align-items: flex-start; margin-bottom: 30px;">
                                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 20px; flex-shrink: 0;">
                                        <img src="assets/home_page/img/phone.png" class="contact-img" alt="">
                                    </div>
                                    <div>
                                        <h5 style="color: white; margin-bottom: 8px; font-weight: 600;">{{ __('phone') }}</h5>
                                        <p style="color: rgba(255,255,255,0.8); margin: 0;">{{ $settings['mobile'] ?? '' }}</p>
                                    </div>
                                </div>
                            @endif

                            @if (isset($settings['mail_username']))
                                <div class="contact-item" style="display: flex; align-items: flex-start; margin-bottom: 30px;">
                                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 20px; flex-shrink: 0;">
                                        <img src="assets/home_page/img/gmail.png" class="contact-img" alt="">
                                    </div>
                                    <div>
                                        <h5 style="color: white; margin-bottom: 8px; font-weight: 600;">{{ __('email') }}</h5>
                                        <p style="color: rgba(255,255,255,0.8); margin: 0;">{{ $settings['mail_username'] ?? '' }}</p>
                                    </div>
                                </div>
                            @endif

                            @if (isset($settings['address']))
                                <div class="contact-item" style="display: flex; align-items: flex-start;">
                                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 20px; flex-shrink: 0;">
                                        <img src="assets/home_page/img/location.png" class="contact-img" alt="">
                                    </div>
                                    <div>
                                        <h5 style="color: white; margin-bottom: 8px; font-weight: 600;">{{ __('location') }}</h5>
                                        <p style="color: rgba(255,255,255,0.8); margin: 0;">{{ $settings['address'] }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Mobile App Download Section -->
    <div data-aos="zoom-out-up" data-aos-delay="100" class="d-sm-block d-md-none mobile-footer">
        <div class="text-center">
            <div class="mx-auto text-light mb-4">
                <h2 style="font-weight: 800; margin-bottom: 16px;">{{ __('start_learning_by') }}<br>{{ __('downloading_apps') }}.</h2>
                <p style="opacity: 0.9; font-size: 16px;">Access your school management system on the go with our mobile apps.</p>
            </div>
            <div class="mx-auto text-light">
                <a href="{{ $settings['ios_app_link'] ?? '/' }}" target="_blank" class="btn-apple mx-2 mb-3">
                    <img src="{{ asset('assets/home_page/img/apple.svg') }}" class="mx-2" alt="">{{ __('apple_store') }}
                </a>
                <a href="{{ $settings['app_link'] ?? '/' }}" target="_blank" class="btn-play mx-2 mb-3">
                    <img src="{{ asset('assets/home_page/img/playstore.svg') }}" class="mx-2" alt="">{{ __('play_store') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Desktop App Download Section -->
    <div data-aos="zoom-out-up" data-aos-delay="200" class="col-md-10 d-none d-md-block mx-auto footer-1">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="text-light">
                    <h2 style="font-weight: 800; margin-bottom: 16px; font-size: 2.5rem;">{{ __('start_learning_by') }}<br>{{ __('downloading_apps') }}.</h2>
                    <p style="opacity: 0.9; font-size: 18px; margin-bottom: 0;">Take your school management system anywhere with our powerful mobile applications. Stay connected, manage tasks, and enhance learning on the go.</p>
                </div>
            </div>
            <div class="col-md-5 text-center">
                <div class="d-flex flex-column gap-3">
                    <a href="{{ $settings['ios_app_link'] ?? '/' }}" target="_blank" class="btn-apple">
                        <img src="{{ asset('assets/home_page/img/apple.svg') }}" class="mx-2" alt="">{{ __('apple_store') }}
                    </a>
                    <a href="{{ $settings['app_link'] ?? '/' }}" target="_blank" class="btn-play">
                        <img src="{{ asset('assets/home_page/img/playstore.svg') }}" class="mx-2" alt="">{{ __('play_store') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 footer-contact">
                        <a href="{{ url('/') }}" class="d-inline-block mb-3">
                            <img src="{{ $settings['horizontal_logo'] ?? asset('assets/home_page/img/Logo.svg') }}" alt="" style="max-height: 50px;">
                        </a>
                        <h4 style="color: white; font-weight: 700; margin-bottom: 20px;">
                            <strong>{{ $settings['system_name'] ?? 'eSchool Virtual Education' }}</strong>
                        </h4>
                        <p style="color: rgba(255,255,255,0.8); line-height: 1.6; font-size: 16px;">
                            {{ $settings['short_description'] ?? 'Empowering educational institutions with cutting-edge technology and innovative solutions for better learning outcomes.' }}
                        </p>
                    </div>

                    <div class="col-lg-4 col-md-6 footer-links">
                        <h4 style="color: white; font-weight: 700; margin-bottom: 30px;">{{ __('links') }}</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right" style="color: var(--accent-color);"></i> <a href="{{ url('/') }}" class="scrollto">{{ __('home') }}</a></li>
                            <li><i class="bx bx-chevron-right" style="color: var(--accent-color);"></i> <a href="#feature" class="scrollto">{{ __('features') }}</a></li>
                            <li><i class="bx bx-chevron-right" style="color: var(--accent-color);"></i> <a href="#pricing" class="scrollto">{{ __('pricing') }}</a></li>
                            <li><i class="bx bx-chevron-right" style="color: var(--accent-color);"></i> <a href="#faq" class="scrollto">{{ __('faq') }}</a></li>
                            <li><i class="bx bx-chevron-right" style="color: var(--accent-color);"></i> <a href="#contact" class="scrollto">{{ __('contact') }}</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-4 col-md-6 footer-links">
                        @if (!empty($settings['facebook']) || !empty($settings['instagram']) || !empty($settings['linkedin']))
                            <h4 style="color: white; font-weight: 700; margin-bottom: 30px;">{{ __('follow_us') }}</h4>
                        @endif
                        <div class="social-links">
                            @if (!empty($settings['facebook']))
                                <a href="{{ $settings['facebook'] }}" target="_blank" class="facebook">
                                    <img src="{{ asset('assets/home_page/img/facebook.png') }}" alt="">
                                </a>
                            @endif
                            @if (!empty($settings['instagram']))
                                <a href="{{ $settings['instagram'] }}" target="_blank" class="instagram">
                                    <img src="{{ asset('assets/home_page/img/instagram.png') }}" alt="">
                                </a>
                            @endif
                            @if (!empty($settings['linkedin']))
                                <a href="{{ $settings['linkedin'] }}" target="_blank" class="linkedin">
                                    <img src="{{ asset('assets/home_page/img/linkedIn.png') }}" alt="">
                                </a>
                            @endif
                        </div>

                        @if (!empty($settings['facebook']) || !empty($settings['instagram']) || !empty($settings['linkedin']))
                            <div style="margin-top: 30px;">
                                <h5 style="color: white; font-weight: 600; margin-bottom: 16px;">Stay Updated</h5>
                                <p style="color: rgba(255,255,255,0.8); font-size: 14px;">
                                    Follow us on social media for the latest updates, tips, and educational insights.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <hr style="border-color: rgba(255,255,255,0.1); margin: 40px 0 0;">
        <div class="container footer-bottom clearfix text-center" style="padding: 30px 0;">
            <div style="color: rgba(255,255,255,0.8); font-size: 16px;">
                {!! $settings['footer_text'] ?? '<p>&copy; ' . date("Y") . ' <strong><span><a href="https://qtechafrica.com/" target="_blank" rel="noopener noreferrer" style="color: var(--accent-color); text-decoration: none;">QTECHAFRICA</a></span></strong>. All Rights Reserved</p>' !!}
            </div>
        </div>
    </footer>

    <div id="preloader" style="background: var(--gradient-primary);"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center" style="background: var(--gradient-primary); border-radius: 12px; box-shadow: var(--shadow-medium);">
        <i class="bi bi-arrow-up-short" style="color: white; font-size: 24px;"></i>
    </a>

    <script>
        // Modern scroll effects
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
                header.style.backdropFilter = 'blur(20px)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.9)';
            }
        });

        // Enhanced form animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 8px 25px rgba(5, 150, 105, 0.15)';
            });

            input.addEventListener('blur', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'var(--shadow-soft)';
            });
        });

        // Smooth reveal animations for FAQ
        document.querySelectorAll('.faq-question a').forEach(faqItem => {
            faqItem.addEventListener('click', function() {
                const isCollapsed = this.classList.contains('collapsed');
                if (!isCollapsed) {
                    this.querySelector('.icon-show').style.transform = 'rotate(180deg)';
                } else {
                    this.querySelector('.icon-show').style.transform = 'rotate(0deg)';
                }
            });
        });
    </script>
@endsection
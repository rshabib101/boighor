<!DOCTYPE html>
<html lang="bn" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'বইঘর')) - বাংলা বইয়ের সেরা ঠিকানা</title>
    <meta name="description" content="@yield('meta_description', 'বইঘর - বাংলা ইসলামিক, উপন্যাস, কবিতা, শিক্ষামূলক সব ধরনের PDF বই বিনামূল্যে ডাউনলোড করুন।')">
    <meta name="keywords" content="@yield('meta_keywords', 'বাংলা বই, pdf বই, বাংলা উপন্যাস, ইসলামিক বই, ফ্রি বই ডাউনলোড')">
    <!-- OpenGraph -->
    <meta property="og:title" content="@yield('title', 'বইঘর')">
    <meta property="og:description" content="@yield('meta_description', 'বাংলা বইয়ের সেরা ঠিকানা')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#6366f1">
    <link rel="apple-touch-icon" href="/images/icon-192.png">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Noto+Serif+Bengali:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Preloader -->
    <div id="preloader"><div class="spinner"></div></div>

    <!-- Header -->
    <header class="site-header" id="siteHeader">
        <div class="container">
            <div class="header-inner">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="logo">
                    <span class="logo-icon"><i class="fas fa-book-open"></i></span>
                    <span class="logo-text">বই<span class="accent">ঘর</span></span>
                </a>

                <!-- Search Bar -->
                <form action="{{ route('search') }}" method="GET" class="search-form" id="searchForm">
                    <div class="search-wrapper">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="বইয়ের নাম, লেখক বা ক্যাটাগরি লিখুন..." class="search-input" autocomplete="off" id="searchInput">
                        <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                    </div>
                </form>

                <!-- Nav Actions -->
                <div class="header-actions">
                    <!-- Dark Mode -->
                    <button class="icon-btn" id="darkModeToggle" title="Dark Mode">
                        <i class="fas fa-moon" id="darkIcon"></i>
                    </button>

                    @auth
                        <!-- Notifications -->
                        <div class="dropdown">
                            <button class="icon-btn notif-btn" id="notifBtn">
                                <i class="fas fa-bell"></i>
                                @if(auth()->user()->unreadNotificationsCount() > 0)
                                    <span class="badge">{{ auth()->user()->unreadNotificationsCount() }}</span>
                                @endif
                            </button>
                        </div>

                        <!-- User Menu -->
                        <div class="dropdown user-dropdown">
                            <button class="user-btn" id="userMenuBtn">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-avatar">
                                <span class="user-name d-none d-md-inline">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu" id="userMenu">
                                <div class="dropdown-header">
                                    <strong>{{ auth()->user()->name }}</strong>
                                    <span class="points-badge"><i class="fas fa-coins"></i> {{ number_format(auth()->user()->points) }} পয়েন্ট</span>
                                </div>
                                <a href="{{ route('user.profile') }}" class="dropdown-item"><i class="fas fa-user"></i> প্রোফাইল</a>
                                <a href="{{ route('user.history') }}" class="dropdown-item"><i class="fas fa-download"></i> ডাউনলোড হিস্ট্রি</a>
                                <a href="{{ route('user.favorites') }}" class="dropdown-item"><i class="fas fa-heart"></i> ফেভারিট</a>
                                <a href="{{ route('user.wallet') }}" class="dropdown-item"><i class="fas fa-wallet"></i> ওয়ালেট</a>
                                <a href="{{ route('user.notifications') }}" class="dropdown-item"><i class="fas fa-bell"></i> নোটিফিকেশন</a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt"></i> লগআউট</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">লগইন</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">রেজিস্টার</a>
                    @endauth

                    <!-- Mobile menu toggle -->
                    <button class="icon-btn mobile-menu-btn d-md-none" id="mobileMenuBtn">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Main Navigation -->
            <nav class="main-nav" id="mainNav">
                <ul class="nav-list">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fas fa-home"></i> হোম</a></li>
                    <li class="dropdown-nav">
                        <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}"><i class="fas fa-th-large"></i> ক্যাটাগরি <i class="fas fa-chevron-down"></i></a>
                        <div class="mega-menu">
                            <div class="mega-menu-grid">
                                @php $cats = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get(); @endphp
                                @foreach($cats as $cat)
                                    <a href="{{ route('categories.show', $cat->slug) }}" class="mega-item">
                                        <span class="mega-icon" style="background: {{ $cat->color }}20; color: {{ $cat->color }}">
                                            <i class="{{ $cat->icon }}"></i>
                                        </span>
                                        {{ $cat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </li>
                    <li><a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.*') ? 'active' : '' }}"><i class="fas fa-books"></i> সব বই</a></li>
                    <li><a href="{{ route('authors.index') }}" class="{{ request()->routeIs('authors.*') ? 'active' : '' }}"><i class="fas fa-user-edit"></i> লেখক</a></li>
                    @auth
                        <li><a href="{{ route('user.wallet') }}"><i class="fas fa-coins"></i> ওয়ালেট</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="alert-toast success" id="toastMsg">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-toast error" id="toastMsg">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-top">
            <div class="container">
                <div class="footer-grid">
                    <!-- About -->
                    <div class="footer-col">
                        <div class="footer-logo">
                            <i class="fas fa-book-open"></i> বই<span class="accent">ঘর</span>
                        </div>
                        <p>বইঘর হলো বাংলা বইয়ের সেরা অনলাইন প্ল্যাটফর্ম। এখানে হাজারো বাংলা বই বিনামূল্যে পড়ুন ও ডাউনলোড করুন।</p>
                        <div class="social-links">
                            <a href="{{ \App\Models\SiteSetting::get('facebook_url', '#') }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="{{ \App\Models\SiteSetting::get('telegram_url', '#') }}" target="_blank"><i class="fab fa-telegram-plane"></i></a>
                            <a href="{{ \App\Models\SiteSetting::get('youtube_url', '#') }}" target="_blank"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="footer-col">
                        <h4>ক্যাটাগরি</h4>
                        <ul>
                            @foreach($cats->take(6) as $cat)
                                <li><a href="{{ route('categories.show', $cat->slug) }}"><i class="{{ $cat->icon }}"></i> {{ $cat->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Quick Links -->
                    <div class="footer-col">
                        <h4>দ্রুত লিংক</h4>
                        <ul>
                            <li><a href="{{ route('home') }}">হোম</a></li>
                            <li><a href="{{ route('books.index') }}">সব বই</a></li>
                            <li><a href="{{ route('authors.index') }}">লেখক</a></li>
                            @auth
                            <li><a href="{{ route('user.wallet') }}">ওয়ালেট</a></li>
                            @else
                            <li><a href="{{ route('register') }}">রেজিস্ট্রেশন করুন</a></li>
                            @endauth
                        </ul>
                    </div>

                    <!-- Telegram Join -->
                    <div class="footer-col">
                        <h4>আমাদের সাথে থাকুন</h4>
                        <a href="{{ \App\Models\SiteSetting::get('telegram_url', '#') }}" target="_blank" class="telegram-join-btn">
                            <i class="fab fa-telegram-plane"></i>
                            Telegram Channel Join করুন
                        </a>
                        <div class="app-install-prompt" id="pwaInstall" style="display:none">
                            <button id="installBtn" class="install-btn">
                                <i class="fas fa-download"></i> App ইনস্টল করুন
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p>{{ \App\Models\SiteSetting::get('footer_text', '© ' . date('Y') . ' বইঘর। সর্বস্বত্ব সংরক্ষিত।') }}</p>
            </div>
        </div>
    </footer>

    <!-- Floating Actions -->
    <div class="floating-actions">
        <a href="{{ \App\Models\SiteSetting::get('telegram_url', '#') }}" target="_blank" class="fab-btn telegram" title="Telegram Join">
            <i class="fab fa-telegram-plane"></i>
        </a>
        <button class="fab-btn scroll-top" id="scrollTop" title="উপরে যান">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')

    <script>
        // PWA Install
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            document.getElementById('pwaInstall').style.display = 'block';
        });
        document.getElementById('installBtn')?.addEventListener('click', () => {
            deferredPrompt?.prompt();
        });

        // Register Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="M!VENT - Temukan dan beli tiket event terbaik" />
    <meta name="author" content="M!VENT Team" />
    <title>@yield('title', 'M!VENT — Temukan Event Terbaik')</title>
    @vite(['resources/css/style.css'])
</head>

<body>
    <header>
        @include('partials.navbar')
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-inner">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="footer-brand-header">
                        <svg class="logo-icon" viewBox="0 0 66 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2" y="2" width="62" height="41" rx="6" fill="#FFE047" />
                            <circle cx="2" cy="22.5" r="6" fill="#2B293D" />
                            <circle cx="64" cy="22.5" r="6" fill="#2B293D" />
                            <line x1="20" y1="6" x2="20" y2="39" stroke="#2B293D" stroke-width="2.5" stroke-dasharray="4 4" />
                            <path d="M36 14L38.5 19.5L44.5 20.2L40 24.2L41.2 30L36 27L30.8 30L32 24.2L27.5 20.2L33.5 19.5L36 14Z" fill="#2B293D" />
                        </svg>
                        <span class="footer-logo">M!VENT</span>
                    </div>
                    <p>Platform tiket event terpercaya di Indonesia untuk menemukan konser, seminar, pameran, hingga festival favoritmu tanpa ribet.</p>
                </div>

                <div class="footer-col">
                    <h4>Navigasi</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('home') }}#katalog-event">Jelajahi Event</a></li>
                        <li><a href="{{ route('eo.login') }}">Login Event Organizer</a></li>
                        <li><a href="{{ route('eo.register') }}">Daftar sebagai EO</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Bantuan &amp; Akses</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}#katalog-event">Cara Pemesanan Tiket</a></li>
                        <li><a href="{{ route('eo.register') }}">Publikasi Event</a></li>
                        <li><a href="{{ route('admin.login') }}">Portal Administrator</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} M!VENT. All rights reserved.</p>
                <a href="{{ route('admin.login') }}" class="footer-admin-link">Admin Portal</a>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (!navbar) return;
            if (window.scrollY > 40) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Hamburger menu toggle
        document.getElementById('hamburger-btn')?.addEventListener('click', function() {
            const menu = document.querySelector('.menu');
            menu?.classList.toggle('menu-open');
            const icon = this.querySelector('.hamburger-icon');
            if (icon) icon.textContent = menu?.classList.contains('menu-open') ? '✕' : '☰';
        });
    </script>

    @stack('scripts')
</body>

</html>
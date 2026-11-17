<nav id="navbar">
    <div class="navbar-inner">
        {{-- Brand Logo dengan ikon tiket Figma --}}
        <a href="{{ route('home') }}" class="logo-link">
            <svg class="logo-icon" viewBox="0 0 66 45" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <rect x="2" y="2" width="62" height="41" rx="6" fill="#FFE047" />
                <circle cx="2" cy="22.5" r="6" fill="#2B293D" />
                <circle cx="64" cy="22.5" r="6" fill="#2B293D" />
                <line x1="20" y1="6" x2="20" y2="39" stroke="#2B293D" stroke-width="2.5" stroke-dasharray="4 4" />
                <path d="M36 14L38.5 19.5L44.5 20.2L40 24.2L41.2 30L36 27L30.8 30L32 24.2L27.5 20.2L33.5 19.5L36 14Z" fill="#2B293D" />
            </svg>
            <span class="logo">M<span class="logo-accent">!</span>VENT</span>
        </a>

        {{-- Hamburger button (mobile only) --}}
        <button id="hamburger-btn" class="hamburger-btn" aria-label="Toggle menu" type="button">
            <span class="hamburger-icon">☰</span>
        </button>

        {{-- Menu items --}}
        <div class="menu">
            <div class="nav-links-center">
                <a href="{{ route('home') }}" class="nav-link-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    Home
                </a>
                <a href="{{ route('home') }}#katalog-event" class="nav-link-item">
                    Jelajahi Event
                </a>
                <a href="{{ route('home') }}#kategori-event" class="nav-link-item">
                    Kategori
                </a>
            </div>

            <div class="nav-actions">
                <a href="{{ route('eo.login') }}" class="btn-masuk">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                    </svg>
                    Login EO
                </a>

                <a href="{{ route('eo.register') }}" class="btn-eo">
                    Daftar sebagai EO
                </a>
            </div>
        </div>
    </div>
</nav>
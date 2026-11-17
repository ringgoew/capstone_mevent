<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pendaftaran EO — M!VENT')</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/eo.css'])
    @else
    <link rel="stylesheet" href="{{ asset('css/eo.css') }}">
    @endif
</head>

<body class="eoa-body">

    <!-- NAVBAR DEEP NAVY M!VENT (TANPA SIDEBAR DASHBOARD) -->
    <nav class="eoa-navbar">
        <div class="eoa-navbar-inner">
            <a href="{{ route('home') }}" class="eoa-logo-link">
                <span class="eoa-logo-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 28" fill="none">
                        <path d="M4 0H36C38.2091 0 40 1.79086 40 4V9.5C37.5147 9.5 35.5 11.5147 35.5 14C35.5 16.4853 37.5147 18.5 40 18.5V24C40 26.2091 38.2091 28 36 28H4C1.79086 28 0 26.2091 0 24V18.5C2.48528 18.5 4.5 16.4853 4.5 14C4.5 11.5147 2.48528 9.5 0 9.5V4C0 1.79086 1.79086 0 4 0Z" fill="#FFE047" />
                        <line x1="12.5" y1="3" x2="12.5" y2="25" stroke="#2B293D" stroke-width="2" stroke-dasharray="2.5 2.5" stroke-linecap="round" />
                        <path d="M25.5 8.2L27.15 11.55L30.85 12.09L28.17 14.7L28.8 18.38L25.5 16.64L22.2 18.38L22.83 14.7L20.15 12.09L23.85 11.55L25.5 8.2Z" fill="#2B293D" />
                    </svg>
                </span>
                <span class="eoa-logo-text">M<span class="eoa-logo-bang">!</span>VENT</span>
            </a>

            <a href="{{ route('home') }}" class="eoa-nav-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </nav>

    <main class="eoa-main">
        @yield('content')
    </main>

    <footer class="eoa-footer">
        <p>&copy; {{ date('Y') }} <strong>M!VENT</strong> — Platform Manajemen Event &amp; Tiket Konser.</p>
    </footer>

    @stack('scripts')
</body>

</html>
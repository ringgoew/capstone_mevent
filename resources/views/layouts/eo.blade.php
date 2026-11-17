<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="M!VENT — Dashboard Event Organizer" />
    <title>@yield('title', 'Dashboard EO — M!VENT')</title>
    @vite(['resources/css/eo.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="eo-body">

    @php
        $isAuthFlow = request()->routeIs('eo.otp*', 'eo.pending*', 'eo.kyc.pending');
    @endphp

    @if(!$isAuthFlow)
        @include('partials.sidebar_eo')

        <!-- Mobile Topbar with Hamburger Toggle -->
        <header class="eo-mobile-bar">
            <button type="button" class="eo-hamburger" id="eoHamburgerBtn" aria-label="Buka Menu Sidebar">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="eo-mobile-brand">M<span>!</span>VENT</div>
            <div class="eo-mobile-user">
                {{ session('eo_nama', 'EO') }}
            </div>
        </header>
    @endif

    <main class="main {{ $isAuthFlow ? 'main-no-sidebar' : '' }}">
        @yield('content')

        <footer class="eo-footer">
            <p>&copy; {{ date('Y') }} M!VENT &mdash; Dashboard Event Organizer</p>
        </footer>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.getElementById('eoHamburgerBtn');
            const sidebar = document.getElementById('eoSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const closeBtn = document.getElementById('sidebarCloseBtn');

            function openSidebar() {
                if (sidebar) sidebar.classList.add('sidebar-open');
                if (backdrop) backdrop.classList.add('show');
            }

            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('sidebar-open');
                if (backdrop) backdrop.classList.remove('show');
            }

            if (hamburger) hamburger.addEventListener('click', openSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        });

        function confirmLogout() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: "Apakah Anda yakin ingin keluar dari sesi Dashboard EO?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#38BDF8',
                    cancelButtonColor: '#94A3B8',
                    confirmButtonText: 'Ya, Keluar',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('logout-form');
                        if (form) form.submit();
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin keluar dari sesi Dashboard EO?')) {
                    const form = document.getElementById('logout-form');
                    if (form) form.submit();
                }
            }
        }
    </script>
</body>
</html>

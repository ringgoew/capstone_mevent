<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="M!VENT Admin Panel">
    <title>@yield('title', 'Admin — M!VENT')</title>

    <!-- Google Fonts sesuai DESIGN.md: Lalezar, Montserrat, Open Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Montserrat:wght@500;600;700;800&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/admin.css', 'resources/js/app.js'])
</head>

<body>
    <div class="admin-shell">
        <!-- Sidebar Navigasi Admin -->
        @include('partials.sidebar_admin')

        <!-- Backdrop Mobile Sidebar -->
        <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleAdminSidebar(false)"></div>

        <!-- Area Kerja Utama -->
        <div class="admin-workspace">
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button type="button" class="mobile-menu-toggle" aria-label="Buka Menu" onclick="toggleAdminSidebar(true)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>

                    <div class="topbar-title-group">
                        <h2>@yield('page-title', 'Panel Administrator')</h2>
                        <p>@yield('page-subtitle', 'Sistem Manajemen & Moderasi Platform M!VENT')</p>
                    </div>
                </div>

                <div class="topbar-right">
                    <span class="topbar-status-pill">
                        <span class="status-dot"></span>
                        Sistem Aktif
                    </span>

                    <div class="admin-profile-pill">
                        <span class="admin-profile-icon">
                            {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
                        </span>
                        <span class="admin-name">
                            Halo, {{ session('admin_name', 'Administrator') }}
                        </span>
                    </div>
                </div>
            </header>

            <main class="container">
                @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                <div class="alert error">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                <div class="alert error">
                    @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                @yield('content')
            </main>

            <footer class="admin-footer">
                <span>&copy; 2026 <strong>M!VENT</strong> &mdash; Admin Control Panel</span>
                <span>Platform Manajemen Event &amp; Tiket Konser</span>
            </footer>
        </div>
    </div>

    <script>
        function toggleAdminSidebar(forceState) {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (!sidebar || !backdrop) return;

            const shouldOpen = typeof forceState === 'boolean' ?
                forceState :
                !sidebar.classList.contains('open');

            sidebar.classList.toggle('open', shouldOpen);
            backdrop.classList.toggle('show', shouldOpen);
        }
    </script>
</body>

</html>
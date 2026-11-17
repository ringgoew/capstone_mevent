<aside class="admin-sidebar" id="adminSidebar" aria-label="Navigasi Admin">
    <!-- Brand Logo M!VENT -->
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="logo-link">
            <span class="logo-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                    <path d="M13 5v2" />
                    <path d="M13 17v2" />
                    <path d="M13 11v2" />
                </svg>
            </span>
            <span class="logo-text">M<span>!</span>VENT</span>
            <span class="role-pill">Admin</span>
        </a>

        <button type="button" class="sidebar-close-btn" aria-label="Tutup Menu" onclick="toggleAdminSidebar(false)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!-- Daftar Menu Sidebar -->
    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Menu Utama</div>

        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="sidebar-link-left">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                </svg>
                <span>Dashboard</span>
            </span>
        </a>

        <a href="{{ route('admin.kyc.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.kyc.*') ? 'active' : '' }}">
            <span class="sidebar-link-left">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <polyline points="16 11 18 13 22 9"></polyline>
                </svg>
                <span>Pemeriksaan KYC</span>
            </span>
        </a>

        <a href="{{ route('admin.event-verification.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.event-verification.*') ? 'active' : '' }}">
            <span class="sidebar-link-left">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <path d="m9 16 2 2 4-4"></path>
                </svg>
                <span>Verifikasi Event</span>
            </span>
            @if (isset($data['event_pending']) && $data['event_pending'] > 0)
            <span class="sidebar-count-badge">{{ $data['event_pending'] }}</span>
            @endif
        </a>

        <div class="sidebar-section-label">Monitoring</div>

        <a href="{{ route('admin.dashboard') }}#ringkasan-operasional" class="sidebar-link">
            <span class="sidebar-link-left">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                <span>Laporan &amp; Ringkasan</span>
            </span>
        </a>
    </nav>

    <!-- Profil Ringkas & Tombol Logout -->
    <div class="sidebar-footer">
        <div class="sidebar-user-card">
            <div class="sidebar-avatar">
                {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ session('admin_name', 'Administrator') }}</div>
                <div class="sidebar-user-role">Super Administrator</div>
            </div>
        </div>

        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <button type="button" onclick="confirmAdminLogout()" class="sidebar-logout-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span>Logout</span>
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmAdminLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: "Apakah Anda yakin ingin keluar dari panel admin?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2B293D',
                cancelButtonColor: '#8C8C9E',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</aside>
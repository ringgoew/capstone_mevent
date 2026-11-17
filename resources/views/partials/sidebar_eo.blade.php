@php
    $eoUser = session('eo_user_id')
        ? \Illuminate\Support\Facades\DB::table('users')->where('id', session('eo_user_id'))->first()
        : null;
    $isEoVerified = $eoUser && $eoUser->status === 'verified';
@endphp

<aside class="sidebar eos-sidebar" id="eoSidebar">
    <div class="sidebar-header eos-sidebar-header">
        <a href="{{ route('eo.dashboard') }}" class="logo eos-logo-link">
            <span class="eos-logo-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 28" fill="none">
                    <path
                        d="M4 0H36C38.2091 0 40 1.79086 40 4V9.5C37.5147 9.5 35.5 11.5147 35.5 14C35.5 16.4853 37.5147 18.5 40 18.5V24C40 26.2091 38.2091 28 36 28H4C1.79086 28 0 26.2091 0 24V18.5C2.48528 18.5 4.5 16.4853 4.5 14C4.5 11.5147 2.48528 9.5 0 9.5V4C0 1.79086 1.79086 0 4 0Z"
                        fill="#FFE047" />
                    <line x1="12.5" y1="3" x2="12.5" y2="25" stroke="#2B293D" stroke-width="2"
                        stroke-dasharray="2.5 2.5" stroke-linecap="round" />
                    <path
                        d="M25.5 8.2L27.15 11.55L30.85 12.09L28.17 14.7L28.8 18.38L25.5 16.64L22.2 18.38L22.83 14.7L20.15 12.09L23.85 11.55L25.5 8.2Z"
                        fill="#2B293D" />
                </svg>
            </span>
            <span class="eos-logo-text">M<span class="eos-logo-bang">!</span>VENT</span>
            <span class="eos-role-badge">EO</span>
        </a>

        <button type="button" class="sidebar-close-btn eos-close-btn" id="sidebarCloseBtn" aria-label="Tutup Menu">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="eos-perforation" aria-hidden="true"></div>

    <div class="eos-nav-scroll">
        <div class="menu-title eos-menu-title">Menu Utama</div>

        <a href="{{ route('eo.dashboard') }}"
            class="eos-nav-link {{ request()->routeIs('eo.dashboard') ? 'active' : '' }}">
            <span class="eos-nav-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                </svg>
            </span>
            <span class="eos-nav-label">Dashboard</span>
        </a>

        <a href="{{ route('eo.events.index') }}"
            class="eos-nav-link {{ request()->routeIs('eo.events.index*') || request()->routeIs('eo.events.show*') ? 'active' : '' }}">
            <span class="eos-nav-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14l-5-5 1.41-1.41L12 14.17l7.59-7.59L21 8l-9 9z" />
                </svg>
            </span>
            <span class="eos-nav-label">Daftar Event</span>
        </a>

    <a href="{{ route('eo.payout.index') }}" class="{{ request()->routeIs('eo.payout.index') ? 'active' : '' }}">
        Payout
    </a>

        <a href="{{ route('eo.reports.index') }}"
            class="eos-nav-link {{ request()->routeIs('eo.reports*') ? 'active' : '' }}">
            <span class="eos-nav-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                </svg>
            </span>
            <span class="eos-nav-label">Laporan Penjualan</span>
        </a>

        <a href="{{ route('eo.payout.index') }}"
            class="eos-nav-link {{ request()->routeIs('eo.payout*') ? 'active' : '' }}">
            <span class="eos-nav-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                </svg>
            </span>
            <span class="eos-nav-label">Pengajuan Payout</span>
        </a>
    </div>

    <div class="sidebar-spacer"></div>

    <div class="sidebar-footer eos-sidebar-footer">
        <form id="logout-form" action="{{ route('eo.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <button type="button" class="sidebar-logout-btn eos-logout-btn" onclick="confirmLogout()">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                viewBox="0 0 24 24">
                <path
                    d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" />
            </svg>
            <span>Keluar (Logout)</span>
        </button>
    </div>
</aside>

<!-- Backdrop overlay for mobile drawer -->
<div class="sidebar-backdrop eos-backdrop" id="sidebarBackdrop"></div>

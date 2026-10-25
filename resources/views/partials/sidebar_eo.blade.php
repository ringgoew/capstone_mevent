<aside class="sidebar">

    <div class="logo">
        Event Management
    </div>

    <a href="{{ route('eo.dashboard') }}" class="{{ request()->routeIs('eo.dashboard') ? 'active' : '' }}">
        Dashboard
    </a>

    <a href="{{ route('eo.events.index') }}" class="{{ request()->routeIs('eo.events.index') ? 'active' : '' }}">
        Daftar Event
    </a>

    <a href="{{ route('eo.events.create') }}" class="{{ request()->routeIs('eo.events.create') ? 'active' : '' }}">
        Buat Event
    </a>

    <a href="{{ route('eo.dashboard') }}">
        Laporan
    </a>

    <a href="{{ route('eo.dashboard') }}">
        Payout
    </a>

    <div class="menu-title">
        Akun
    </div>

    <a href="{{ route('eo.dashboard') }}">
        Profil
    </a>

    <!-- Tombol Logout -->
    <form id="logout-form" action="{{ route('eo.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <button type="button" onclick="confirmLogout()" class="btn btn-danger">
        Logout
    </button>

    <!-- Script SweetAlert2 untuk Konfirmasi -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: "Apakah Anda yakin ingin keluar dari sistem?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika IYA: Hapus sesi dan arahkan ke home (submit form)
                    document.getElementById('logout-form').submit();
                } else {
                    // Jika tidak: Kembali ke dashboard / batal
                    // Tidak melakukan apa-apa atau tampilkan info batal
                }
            })
        }
    </script>

</aside>
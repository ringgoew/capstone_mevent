<nav>
    <div class="logo">
        Event Management
    </div>

    <div class="admin-area">
        <span class="admin-name">
            Halo, {{ session('admin_name', 'Administrator') }}
        </span>

        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.kyc.index') }}">Pemeriksaan KYC</a>

        <!-- Tombol Logout -->
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <button type="button" onclick="confirmLogout()" class="btn btn-danger">
            Logout
        </button>

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
                        document.getElementById('logout-form').submit();
                    } else {
                    }
                })
            }
        </script>
    </div>
</nav>
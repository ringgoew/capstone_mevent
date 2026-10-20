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

            <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-dark">
                    Logout
                </button>
            </form>
        </div>
</nav>
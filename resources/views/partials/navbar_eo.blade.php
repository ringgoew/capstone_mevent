@vite(['resources/css/style.css'])


<aside class="sidebar">

        <div class="logo">
            Event Management
        </div>

        <a href="{{ route('eo.dashboard') }}" class="active">
            Dashboard
        </a>

        <a href="#">
            Event Saya
        </a>

        <a href="#">
            Buat Event
        </a>

        <a href="#">
            Tiket & Kuota
        </a>

        <a href="#">
            Penjualan Tiket
        </a>

        <a href="#">
            Data Peserta
        </a>

        <a href="#">
            Laporan
        </a>

        <a href="#">
            Payout
        </a>

        <div class="menu-title">
            Akun
        </div>

        <a href="#">
            Profil & Dokumen
        </a>

        <a href="{{ route('home') }}">
            Logout
        </a>

    </aside>
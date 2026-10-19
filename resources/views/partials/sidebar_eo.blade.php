<aside class="sidebar">

    <div class="logo">
        Event Management
    </div>

    <a href="{{ route('eo.dashboard') }}" class="active">
        Dashboard
    </a>

    <a href="{{ route('eo.events.index') }}">
        Daftar Event
    </a>

    <a href="{{ route('eo.events.create') }}">
        Create Event
    </a>

    <a href="{{ route('eo.dashboard') }}">
        Draft Event
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

    <form action="{{ route('eo.logout') }}" method="POST">
        @csrf
        <button type="submit">
            Logout
        </button>
    </form>

</aside>
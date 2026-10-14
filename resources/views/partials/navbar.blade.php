@vite(['resources/css/style.css', 'resources/js/app.js'])

<nav>
    <div class="logo">
        M!VENT
    </div>

    <div class="menu">
        <a href="{{ route('eo.login') }}" class="btn-masuk">
            Masuk
        </a>

        <a href="{{ route('eo.register') }}" class="btn-eo">
            Daftar sebagai EO
        </a>
    </div>    
</nav>
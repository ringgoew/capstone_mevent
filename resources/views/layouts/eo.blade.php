<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="desription" content="..." />
    <meta name="author" content="Denis" />
    <meta name="keywords" content="..." />
    <!-- <link rel="icon" href=""> -->
    <title>@yield('title', 'M!VENT')</title>
    @vite(['resources/css/eo.css', 'resources/js/app.js'])
    
</head>
<body>

    @if(session('eo_login'))
        <header>    
            @include('partials.sidebar_eo')
        </header>
    @endif

    <main class="main" @if(!session('eo_login')) style="margin-left: 0;" @endif>
        
        @yield('content')

    </main>
    
    <footer class="eo-footer">
        © 2026 Event Management
    </footer>

</body>
</html>
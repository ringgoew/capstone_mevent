<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="desription" content="..." />
    <meta name="author" content="Denis" />
    <meta name="keywords" content="..." />
    <!-- <link rel="icon" href=""> -->
    <title>Event Management</title>
    @vite(['resources/css/style.css'])
    
</head>
<body>

    <header>
        @include('partials.navbar')
    </header>

    <main>
        @yield('content')
    </main>
    
    <footer>
        © 2026 Event Management
    </footer>

</body>
</html>
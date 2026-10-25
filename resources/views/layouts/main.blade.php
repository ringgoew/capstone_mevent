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
        <p>&copy; 2026 Event Management System</p>

        <!-- Tautan tersembunyi / khusus untuk login admin -->
        <p style="margin-top: 10px;">
            <a href="{{ route('admin.login') }}" style="color: #888; font-size: 12px; text-decoration: none;">
                Admin Portal
            </a>
        </p>
    </footer>

    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 40) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>

</html>
</body>

</html>
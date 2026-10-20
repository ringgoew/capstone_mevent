<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin M!VENT')</title>
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
</head>
<body>

<header>
    @include('partials.navbar_admin')
</header>

<main class="container">
        @if (session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>

    <footer style="text-align: center; padding: 20px; color: #777; margin-top: 40px; font-size: 13px;">
        &copy; 2026 Event Management
    </footer>

</body>
</html>
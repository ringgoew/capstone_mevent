<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Pemeriksaan Event — M!VENT Admin')</title>
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
</head>
<body>
    <header>
        @include('partials.sidebar_admin')
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

    <footer class="admin-footer">
        <p>&copy; 2026 M!VENT &mdash; Admin Panel</p>
    </footer>
</body>
</html>

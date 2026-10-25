<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Pemeriksaan Event')</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f5f6fa;
            color: #222;
        }

        nav {
            background: #fff;
            padding: 18px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            box-shadow: 0 2px 8px #0000000d;
        }

        nav a {
            color: #222;
            text-decoration: none;
            margin-right: 16px;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
        }

        .container {
            width: 92%;
            max-width: 1200px;
            margin: 32px auto;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 22px;
            box-shadow: 0 3px 12px #00000008;
        }

        .btn {
            display: inline-block;
            border: 0;
            border-radius: 7px;
            padding: 10px 14px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-dark {
            background: #222;
            color: #fff;
        }

        .btn-green {
            background: #16803c;
            color: #fff;
        }

        .btn-red {
            background: #c62828;
            color: #fff;
        }

        .btn-gray {
            background: #e8e8e8;
            color: #222;
        }

        .alert {
            padding: 13px;
            border-radius: 7px;
            margin-bottom: 18px;
        }

        .success {
            background: #e6f6eb;
            color: #176b37;
        }

        .error {
            background: #fdeaea;
            color: #9e2020;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .pending {
            background: #fff0c2;
            color: #805600;
        }

        .published {
            background: #dff5e5;
            color: #176b37;
        }

        .rejected {
            background: #fde1e1;
            color: #9e2020;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #fafafa;
        }

        .field {
            margin-bottom: 16px;
        }

        .field label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
        }

        input,
        textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 7px;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        @media (max-width: 700px) {
            nav {
                flex-wrap: wrap;
            }

            .container {
                width: 96%;
            }
        }
    </style>
</head>

<body>
    <nav>
        <div class="logo">Event Management — Admin</div>

        <div>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.event-verification.index') }}">
                Pemeriksaan Event
            </a>

            <form action="{{ route('admin.logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-dark">Logout</button>
            </form>
        </div>
    </nav>

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
</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin EventHub')</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f5f6fa;
            color: #202124;
        }

        nav {
            background: #fff;
            padding: 18px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px #0000000d;
        }

        nav a {
            color: #222;
            text-decoration: none;
            margin-right: 18px;
        }

        .logo {
            font-size: 21px;
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
            margin-bottom: 20px;
            box-shadow: 0 3px 12px #00000008;
        }

        .btn {
            display: inline-block;
            border: 0;
            border-radius: 7px;
            padding: 10px 15px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-dark {
            color: #fff;
            background: #222;
        }

        .btn-green {
            color: #fff;
            background: #16803c;
        }

        .btn-red {
            color: #fff;
            background: #c62828;
        }

        .btn-gray {
            color: #222;
            background: #e8e8e8;
        }

        .alert {
            padding: 13px;
            border-radius: 7px;
            margin-bottom: 18px;
        }

        .success {
            color: #176b37;
            background: #e6f6eb;
        }

        .error {
            color: #9e2020;
            background: #fdeaea;
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

        .badge {
            display: inline-block;
            border-radius: 20px;
            padding: 6px 10px;
            font-size: 12px;
        }

        .pending {
            color: #805600;
            background: #fff0c2;
        }

        .verified {
            color: #176b37;
            background: #dff5e5;
        }

        .rejected {
            color: #9e2020;
            background: #fde1e1;
        }

        .field {
            margin-bottom: 16px;
        }

        .field label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .field p {
            margin: 0;
            color: #555;
            overflow-wrap: anywhere;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 7px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        @media (max-width: 700px) {
            nav {
                padding: 16px 4%;
                flex-wrap: wrap;
                gap: 14px;
            }

            .container {
                width: 96%;
            }

            .table-wrap {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

    <nav>
        <div class="logo">EventHub Admin</div>

        <div>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.kyc.index') }}">Pemeriksaan KYC</a>

            <form action="{{ route('admin.logout') }}" method="POST" style="display:inline">
                @csrf
                <button class="btn btn-dark" type="submit">Logout</button>
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

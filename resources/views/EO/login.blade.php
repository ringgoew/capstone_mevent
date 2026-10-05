<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login EO - Event Management</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f7f7f7;
            color: #222;
        }

        nav {
            height: 70px;
            background-color: white;
            display: flex;
            align-items: center;
            padding: 0 60px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .container {
            width: 430px;
            max-width: 90%;
            margin: 70px auto;
            background-color: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ddd;
            border-radius: 7px;
            outline: none;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 7px;
            background-color: #111;
            color: white;
            font-size: 15px;
            cursor: pointer;
        }

        button:hover {
            background-color: #333;
        }

        .error {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #ffe5e5;
            color: #c00;
            border-radius: 7px;
            font-size: 14px;
        }

        .register {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .register a {
            color: #111;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <nav>
        <div class="logo">
            Event POOKIEMAX
        </div>
    </nav>


    <div class="container">

        <h1>Login EO</h1>

        <p class="subtitle">
            Masuk untuk mengelola event kamu.
        </p>


        @if (session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif


        <form action="{{ route('eo.login.authenticate') }}" method="POST">

            @csrf

            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Masukkan email"
                    required
                >

            </div>


            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                >

            </div>


            <button type="submit">
                Login
            </button>

        </form>


        <div class="register">

            Belum memiliki akun EO?

            <a href="{{ route('eo.register') }}">
                Daftar sebagai EO
            </a>

        </div>

    </div>

</body>
</html>
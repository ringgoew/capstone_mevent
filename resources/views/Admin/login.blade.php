<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Admin</title>


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }


        body {
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            background: #f5f5f5;
        }


        .login-container {
            width: 400px;
            max-width: 90%;

            background: white;

            padding: 35px;

            border-radius: 14px;

            box-shadow:
                0 5px 20px rgba(0, 0, 0, 0.08);
        }


        .logo {
            text-align: center;

            font-size: 25px;
            font-weight: bold;

            margin-bottom: 8px;
        }


        .subtitle {
            text-align: center;

            color: #777;

            font-size: 14px;

            margin-bottom: 30px;
        }


        .form-group {
            margin-bottom: 20px;
        }


        label {
            display: block;

            margin-bottom: 7px;

            font-size: 14px;

            font-weight: bold;
        }


        input {
            width: 100%;

            padding: 12px;

            border: 1px solid #ddd;

            border-radius: 8px;

            font-size: 14px;

            outline: none;
        }


        input:focus {
            border-color: #333;
        }


        .btn-login {
            width: 100%;

            padding: 13px;

            background: #111;

            color: white;

            border: none;

            border-radius: 8px;

            cursor: pointer;

            font-size: 15px;
        }


        .btn-login:hover {
            background: #333;
        }


        .error {
            background: #ffe9e9;

            color: #b00000;

            padding: 12px;

            border-radius: 8px;

            margin-bottom: 20px;

            font-size: 14px;
        }


        .success {
            background: #e8f7ed;

            color: #20733a;

            padding: 12px;

            border-radius: 8px;

            margin-bottom: 20px;

            font-size: 14px;
        }


        .dummy {
            margin-top: 20px;

            padding: 15px;

            background: #f3f3f3;

            border-radius: 8px;

            font-size: 13px;

            color: #555;
        }


        .dummy strong {
            display: block;

            margin-bottom: 7px;

            color: #222;
        }
    </style>

</head>


<body>


    <div class="login-container">


        <div class="logo">
            Event Management
        </div>


        <div class="subtitle">
            Login Administrator
        </div>


        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif


        @if ($errors->any())

            <div class="error">

                @foreach ($errors->all() as $error)
                    <div>
                        {{ $error }}
                    </div>
                @endforeach

            </div>

        @endif


        <form action="{{ route('admin.authenticate') }}" method="POST">

            @csrf


            <div class="form-group">

                <label>
                    Email
                </label>

                <input type="email" name="email" placeholder="Masukkan email admin" value="{{ old('email') }}"
                    required>

            </div>


            <div class="form-group">

                <label>
                    Password
                </label>

                <input type="password" name="password" placeholder="Masukkan password" required>

            </div>


            <button type="submit" class="btn-login">
                Login Admin
            </button>

        </form>


        <div class="dummy">

            <strong>
                Akun Dummy
            </strong>

            Email:
            admin@event.test

            <br>

            Password:
            admin123

        </div>


    </div>


</body>

</html>

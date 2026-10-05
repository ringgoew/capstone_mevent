<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verifikasi Email</title>

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
            width: 450px;
            max-width: 90%;
            margin: 70px auto;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            text-align: center;
        }

        h1 {
            margin-bottom: 12px;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 16px;
            text-align: center;
            letter-spacing: 5px;
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

        .info {
            margin-top: 18px;
            padding: 12px;
            background-color: #f0f0f0;
            border-radius: 7px;
            font-size: 13px;
            color: #555;
        }

        .error {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffe5e5;
            color: #c00;
            border-radius: 7px;
            font-size: 14px;
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

        <h1>Verifikasi Email</h1>

        <p class="subtitle">
            Masukkan kode OTP yang dikirim ke email kamu
            untuk melanjutkan pendaftaran sebagai EO.
        </p>

        @if (session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('eo.otp.verify') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Kode OTP</label>

                <input
                    type="text"
                    name="otp"
                    placeholder="Masukkan 6 digit OTP"
                    maxlength="6"
                    required
                >
            </div>

            <button type="submit">
                Verifikasi OTP
            </button>
        </form>

        <div class="info">
            <strong>Demo:</strong> gunakan kode OTP
            <strong>123456</strong>
        </div>

    </div>

</body>
</html>
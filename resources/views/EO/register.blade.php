
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar sebagai EO</title>

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
            width: 500px;
            max-width: 90%;
            margin: 50px auto;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        h1 {
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #555;
        }

        .error {
            color: #c62828;
            font-size: 13px;
            margin-top: 6px;
        }

        .alert {
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 18px;
            background: #ffebee;
            color: #b71c1c;
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

        .back {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #555;
            text-decoration: none;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            nav {
                padding: 0 20px;
            }

            .container {
                padding: 24px;
                margin: 25px auto;
            }
        }
    </style>
</head>

<body>

    <nav>
        <div class="logo">Event POOKIEMAX</div>
    </nav>

    <div class="container">

        <h1>Daftar sebagai EO</h1>

        <p class="subtitle">
            Daftarkan diri kamu sebagai Event Organizer.
        </p>

        @if ($errors->any())
            <div class="alert">
                <strong>Periksa kembali data pendaftaran:</strong>
                <ul style="margin: 8px 0 0 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert">{{ session('error') }}</div>
        @endif

        <form action="{{ route('eo.register.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama">Nama EO / Organisasi</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama') }}"
                    placeholder="Masukkan nama EO"
                    maxlength="255"
                    required
                >
                @error('nama')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="penanggung_jawab">Nama Penanggung Jawab</label>
                <input
                    type="text"
                    id="penanggung_jawab"
                    name="penanggung_jawab"
                    value="{{ old('penanggung_jawab') }}"
                    placeholder="Masukkan nama penanggung jawab"
                    maxlength="255"
                    required
                >
                @error('penanggung_jawab')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email"
                    maxlength="255"
                    required
                >
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="no_telp">No. Telepon</label>
                <input
                    type="tel"
                    id="no_telp"
                    name="no_telp"
                    value="{{ old('no_telp') }}"
                    placeholder="Masukkan nomor telepon"
                    maxlength="20"
                    required
                >
                @error('no_telp')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    minlength="8"
                    required
                >
                @error('password')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi password"
                    minlength="8"
                    required
                >
            </div>

            <button type="submit">
                Daftar sebagai EO
            </button>
        </form>

        <a href="{{ route('home') }}" class="back">
            ← Kembali ke Home
        </a>

    </div>

</body>
</html>

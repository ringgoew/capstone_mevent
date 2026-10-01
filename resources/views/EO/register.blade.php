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
    </style>
</head>

<body>

    <nav>
        <div class="logo">
            Event POOKIEMAX
        </div>
    </nav>

    <div class="container">

        <h1>Daftar sebagai EO</h1>

        <p class="subtitle">
            Daftarkan diri kamu sebagai Event Organizer untuk membuat dan mengelola event.
        </p>

        <form action="{{ route('eo.register.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama EO / Organisasi</label>
                <input
                    type="text"
                    name="nama"
                    placeholder="Masukkan nama EO"
                    required
                >
            </div>

            <div class="form-group">
                <label>Nama Penanggung Jawab</label>
                <input
                    type="text"
                    name="penanggung_jawab"
                    placeholder="Masukkan nama penanggung jawab"
                    required
                >
            </div>

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
                <label>No. Telepon</label>
                <input
                    type="text"
                    name="no_telp"
                    placeholder="Masukkan nomor telepon"
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

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Ulangi password"
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
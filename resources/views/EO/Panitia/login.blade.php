<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Panitia - M!VENT</title>
    <!-- Memanggil eo.css untuk desain form yang seragam dengan sistem -->
    @vite(['resources/css/eo.css'])
</head>

<body style="display: flex; align-items: center; justify-content: center; height: 100vh; background-color: #f7f7f7;">

    <div class="container">
        <div class="text-center">
            <h1>Login Panitia</h1>
            <p class="subtitle">Silakan masuk menggunakan akun yang telah didaftarkan oleh EO.</p>
        </div>

        <!-- Menampilkan Notifikasi Error -->
        @if (session('error'))
            <div class="alert alert-error text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('panitia.login.authenticate') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Email Terdaftar</label>
                <input type="email" name="email" required placeholder="Masukkan email panitia Anda" autofocus>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Masukkan password Anda">
            </div>

            <button type="submit" style="margin-top: 20px;">Masuk ke Panel Panitia</button>
        </form>
    </div>

</body>

</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Menunggu Verifikasi</title>

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
            margin: 80px auto;
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            text-align: center;
        }

        .icon {
            font-size: 55px;
            margin-bottom: 20px;
        }

        h1 {
            margin-bottom: 15px;
        }

        .status {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            background-color: #fff3cd;
            color: #856404;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .info {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
            color: #555;
        }

        .back {
            display: inline-block;
            margin-top: 25px;
            padding: 11px 18px;
            background-color: #111;
            color: white;
            text-decoration: none;
            border-radius: 7px;
        }

        .back:hover {
            background-color: #333;
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

        <div class="icon">
            ⏳
        </div>

        <h1>Pendaftaran Berhasil</h1>

        <div class="status">
            Pending Approval
        </div>

        <p>
            Berkas pendaftaran EO kamu sudah berhasil dikirim.
            Silakan menunggu proses verifikasi dari Admin.
        </p>

        <div class="info">
            Admin akan memeriksa data profil dan dokumen
            yang telah kamu kirim sebelum akun diaktifkan.
        </div>

        <a href="{{ route('home') }}" class="back">
            Kembali ke Home
        </a>

    </div>

</body>
</html>
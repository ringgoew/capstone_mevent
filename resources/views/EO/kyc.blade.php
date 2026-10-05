<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lengkapi Profil EO</title>

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
            width: 600px;
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
            margin-bottom: 20px;
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

        .note {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
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
            margin-top: 10px;
        }

        button:hover {
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

        <h1>Lengkapi Profil EO</h1>

        <p class="subtitle">
            Lengkapi data profil dan dokumen yang diperlukan
            untuk proses verifikasi akun EO.
        </p>

        <form
            action="{{ route('eo.kyc.submit') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="form-group">
                <label>Nama EO / Organisasi</label>

                <input
                    type="text"
                    name="nama_eo"
                    placeholder="Masukkan nama EO / organisasi"
                    required
                >
            </div>

            <div class="form-group">
                <label>Alamat EO</label>

                <input
                    type="text"
                    name="alamat"
                    placeholder="Masukkan alamat lengkap"
                    required
                >
            </div>

            <div class="form-group">
                <label>No. Rekening Bank</label>

                <input
                    type="text"
                    name="rekening"
                    placeholder="Masukkan nomor rekening"
                    required
                >
            </div>

            <div class="form-group">
                <label>KTP</label>

                <input
                    type="file"
                    name="ktp"
                    accept=".jpg,.jpeg,.png,.pdf"
                    required
                >

                <p class="note">
                    Format: JPG, PNG, atau PDF
                </p>
            </div>

            <div class="form-group">
                <label>NPWP</label>

                <input
                    type="file"
                    name="npwp"
                    accept=".jpg,.jpeg,.png,.pdf"
                    required
                >

                <p class="note">
                    Format: JPG, PNG, atau PDF
                </p>
            </div>

            <div class="form-group">
                <label>Akta / Dokumen Legalitas</label>

                <input
                    type="file"
                    name="legalitas"
                    accept=".jpg,.jpeg,.png,.pdf"
                    required
                >

                <p class="note">
                    Upload dokumen legalitas EO / organisasi
                </p>
            </div>

            <div class="form-group">
                <label>Bukti Rekening Bank</label>

                <input
                    type="file"
                    name="bukti_rekening"
                    accept=".jpg,.jpeg,.png,.pdf"
                    required
                >

                <p class="note">
                    Upload buku rekening atau bukti kepemilikan rekening
                </p>
            </div>

            <button type="submit">
                Kirim Berkas Pendaftaran
            </button>

        </form>

    </div>

</body>
</html>
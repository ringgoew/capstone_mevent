
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

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 14px;
            background: white;
        }

        .note {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
        }

        .error {
            color: #c62828;
            font-size: 13px;
            margin-top: 5px;
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
            margin-top: 10px;
        }

        button:hover {
            background-color: #333;
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
        <h1>Lengkapi Profil EO</h1>

        <p class="subtitle">
            Lengkapi data organisasi, identitas, dan rekening
            untuk proses verifikasi akun EO.
        </p>

        @if ($errors->any())
            <div class="alert">
                <strong>Periksa kembali data berikut:</strong>
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

        <form
            action="{{ route('eo.kyc.submit') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="form-group">
                <label for="organization_name">Nama EO / Organisasi</label>
                <input
                    type="text"
                    id="organization_name"
                    name="organization_name"
                    value="{{ old('organization_name') }}"
                    placeholder="Masukkan nama EO / organisasi"
                    maxlength="255"
                    required
                >
            </div>

            <div class="form-group">
                <label for="ktp_number">Nomor KTP</label>
                <input
                    type="text"
                    id="ktp_number"
                    name="ktp_number"
                    value="{{ old('ktp_number') }}"
                    placeholder="Masukkan nomor KTP"
                    maxlength="255"
                    required
                >
            </div>

            <div class="form-group">
                <label for="ktp_image">Foto / Scan KTP</label>
                <input
                    type="file"
                    id="ktp_image"
                    name="ktp_image"
                    accept=".jpg,.jpeg,.png"
                    required
                >
                <p class="note">Format JPG, JPEG, atau PNG. Maksimal 2 MB.</p>
            </div>

            <div class="form-group">
                <label for="npwp_number">Nomor NPWP (Opsional)</label>
                <input
                    type="text"
                    id="npwp_number"
                    name="npwp_number"
                    value="{{ old('npwp_number') }}"
                    placeholder="Masukkan nomor NPWP jika ada"
                    maxlength="255"
                >
            </div>

            <div class="form-group">
                <label for="npwp_image">Foto / Scan NPWP (Opsional)</label>
                <input
                    type="file"
                    id="npwp_image"
                    name="npwp_image"
                    accept=".jpg,.jpeg,.png"
                >
                <p class="note">Format JPG, JPEG, atau PNG. Maksimal 2 MB.</p>
            </div>

            <div class="form-group">
                <label for="bank_name">Nama Bank</label>
                <input
                    type="text"
                    id="bank_name"
                    name="bank_name"
                    value="{{ old('bank_name') }}"
                    placeholder="Contoh: BCA, BRI, Mandiri"
                    maxlength="255"
                    required
                >
            </div>

            <div class="form-group">
                <label for="bank_account_number">Nomor Rekening Bank</label>
                <input
                    type="text"
                    id="bank_account_number"
                    name="bank_account_number"
                    value="{{ old('bank_account_number') }}"
                    placeholder="Masukkan nomor rekening"
                    maxlength="255"
                    required
                >
            </div>

            <div class="form-group">
                <label for="bank_account_holder">Nama Pemilik Rekening</label>
                <input
                    type="text"
                    id="bank_account_holder"
                    name="bank_account_holder"
                    value="{{ old('bank_account_holder') }}"
                    placeholder="Nama sesuai rekening bank"
                    maxlength="255"
                    required
                >
            </div>

            <button type="submit">
                Kirim Berkas Pendaftaran
            </button>
        </form>
    </div>
</body>
</html>

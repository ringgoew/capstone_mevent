<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Panitia - M!VENT</title>
    <style>
        /* CSS Reset Dasar agar tampilan tetap rapi tanpa layout */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #fcfcfc;
            color: #222222;
        }
    </style>
</head>

<body>

    <div style="max-width: 900px; margin: 60px auto; min-height: 50vh; padding: 0 20px;">

        <!-- Kartu Welcome -->
        <div
            style="background: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); text-align: center; border: 1px solid #e4e4e7;">

            <div style="font-size: 40px; margin-bottom: 10px;">👋</div>
            <h1 style="margin-bottom: 10px; color: #111;">Halo, {{ auth()->user()->name }}!</h1>
            <p style="color: #666; font-size: 16px;">Selamat datang di Panel Kepanitiaan. Anda berhasil login dengan hak
                akses sebagai <strong>Panitia</strong>.</p>

            <hr style="margin: 35px 0; border: none; border-top: 1px dashed #ccc;">

            <!-- Menu Aksi Panitia -->
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">

                <!-- Tombol Contoh Fitur Panitia (Misal: Scan Tiket) -->
                <div style="border: 1px solid #e4e4e7; padding: 25px; border-radius: 10px; width: 260px; text-align: center; transition: 0.3s;"
                    onmouseover="this.style.borderColor='#111'" onmouseout="this.style.borderColor='#e4e4e7'">
                    <h3 style="margin-bottom: 10px; font-size: 18px; color: #111;">📷 Scan Tiket Peserta</h3>
                    <p style="font-size: 13px; color: #777; margin-bottom: 20px; line-height: 1.5;">Validasi kehadiran
                        peserta event di pintu masuk menggunakan QR Code.</p>
                    <button
                        style="width: 100%; padding: 12px; background: #111; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
                        Mulai Scan Tiket
                    </button>
                </div>

                <!-- Kartu Logout -->
                <div style="border: 1px solid #e4e4e7; padding: 25px; border-radius: 10px; width: 260px; text-align: center; transition: 0.3s;"
                    onmouseover="this.style.borderColor='#dc3545'" onmouseout="this.style.borderColor='#e4e4e7'">
                    <h3 style="margin-bottom: 10px; font-size: 18px; color: #111;">Akhiri Sesi</h3>
                    <p style="font-size: 13px; color: #777; margin-bottom: 20px; line-height: 1.5;">Keluar dari sistem
                        jika tugas kepanitiaan Anda hari ini sudah selesai.</p>
                    <form action="{{ route('panitia.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            style="width: 100%; padding: 12px; background: #dc3545; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
                            Logout Sekarang
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

</body>

</html>

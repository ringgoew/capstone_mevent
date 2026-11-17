<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Tiket Panitia - M!VENT</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        :root {
            --bg-color: #F8FAFC;
            --primary: #38BDF8;
            --primary-dark: #0EA5E9;
            --text-dark: #0F172A;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Instrument Sans', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-dark);
            min-height: 100vh;
        }

        .navbar {
            background: #FFFFFF;
            border-bottom: 1px solid var(--border-color);
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-logo {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.02em;
            text-decoration: none;
        }

        .brand-logo span {
            color: var(--primary-dark);
        }

        .user-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            background: #F1F5F9;
            padding: 6px 12px;
            border-radius: 9999px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #FFFFFF;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            background: #F1F5F9;
            border-color: #CBD5E1;
        }

        #reader video {
            transform: scaleX(-1) !important;
            -webkit-transform: scaleX(-1) !important;
        }
    </style>
</head>

<body>

    <!-- NAVBAR ATAS (TANPA SIDEBAR) -->
    <nav class="navbar">
        <a href="{{ url('/panitia/dashboard') }}" class="brand-logo">M<span>!</span>VENT</a>
        <div class="user-tag">
            <span
                style="width: 8px; height: 8px; border-radius: 50%; background: #10B981; display: inline-block;"></span>
            {{ auth()->user()->name ?? 'Panitia Event' }} (Panitia)
            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ url('/panitia/dashboard') }}" class="btn-back">
                    Kembali
                </a>
            </div>
        </div>
    </nav>

    <div style="max-width: 600px; margin: 24px auto; padding: 0 20px 40px;">

        <h2 style="margin-bottom: 5px; font-size: 22px;">Scan Tiket Masuk Event</h2>
        <p style="color: #64748b; margin-bottom: 20px; font-size: 14px;">
            Gunakan input manual di bawah atau nyalakan kamera untuk scan QR code.
        </p>

        <!-- KOTAK UTAMA -->
        <div
            style="background: #fff; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

            <!-- 1. KOTAK INPUT KODE MANUAL -->
            <div
                style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #cbd5e1; margin-bottom: 25px;">
                <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #1e293b;">⌨️ Masukkan Kode Tiket Manual</h3>

                <form id="scan-form" style="display: block !important; width: 100% !important;">
                    @csrf
                    <input type="text" id="ticket_code" name="ticket_code"
                        placeholder="Ketik kode tiket di sini (Contoh: TKT-ABC123XYZ)"
                        style="display: block !important; width: 100% !important; min-width: 100% !important; box-sizing: border-box !important; padding: 14px !important; margin-bottom: 12px !important; border: 2px solid #94a3b8 !important; border-radius: 6px !important; font-size: 16px !important; font-family: monospace !important; background: #ffffff !important; color: #000000 !important;"
                        required autocomplete="off">

                    <button type="submit"
                        style="display: block !important; width: 100% !important; background: #2563eb !important; color: #ffffff !important; padding: 14px !important; border: none !important; border-radius: 6px !important; font-weight: bold !important; font-size: 15px !important; cursor: pointer !important;">
                        Proses Kode Tiket
                    </button>
                </form>
            </div>

            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">

            <!-- KOTAK NOTIFIKASI HASIL SCAN -->
            <div id="alert-box"></div>

            <!-- 2. AREA KAMERA (OPSIONAL) -->
            <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #1e293b;">📷 Atau Gunakan Kamera</h3>
            <div
                style="position: relative; width: 100%; background: #0f172a; border-radius: 8px; overflow: hidden; margin-bottom: 15px; min-height: 220px; display: flex; align-items: center; justify-content: center;">
                <div id="reader" style="width: 100%;"></div>
                <div id="camera-status"
                    style="position: absolute; color: #94a3b8; font-size: 13px; text-align: center; padding: 20px;">
                    Menyiapkan kamera...
                </div>
            </div>

            <!-- TOMBOL KONTROL KAMERA -->
            <div style="display: flex; gap: 10px;">
                <button type="button" id="btn-start-cam"
                    style="flex: 1; padding: 10px; background: #0f172a; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Nyalakan Kamera
                </button>
                <button type="button" id="btn-stop-cam"
                    style="display: none; flex: 1; padding: 10px; background: #dc2626; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Matikan Kamera
                </button>
            </div>

        </div>
    </div>

    <!-- Library HTML5 QR Code & jQuery -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const html5QrCode = new Html5Qrcode("reader");
        let isScanning = false;

        // Fungsi kirim kode tiket ke server
        function processTicket(code) {
            $.ajax({
                url: "{{ route('panitia.scan.process') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ticket_code: code
                },
                success: function(response) {
                    $('#alert-box').html(`
                    <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #bbf7d0; font-size: 14px;">
                        <strong>✅ ${response.message}</strong><br>
                        <strong>Nama:</strong> ${response.data.attendee_name}<br>
                        <strong>Event:</strong> ${response.data.event_title}<br>
                        <strong>Tipe:</strong> ${response.data.ticket_type}<br>
                        <strong>Waktu:</strong> ${response.data.checked_in_at}
                    </div>
                `);
                    $('#ticket_code').val('');
                },
                error: function(xhr) {
                    let res = xhr.responseJSON;
                    let errorMsg = res ? res.message : 'Kode tiket tidak valid atau sudah digunakan.';
                    $('#alert-box').html(`
                    <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #fecaca; font-size: 14px;">
                        <strong>❌ Gagal:</strong> ${errorMsg}
                    </div>
                `);
                }
            });
        }

        // Fungsi menyalakan kamera
        function startCamera() {
            $('#camera-status').text('Meminta akses kamera...');
            html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: {
                        width: 200,
                        height: 200
                    }
                },
                (decodedText) => {
                    $('#ticket_code').val(decodedText);
                    processTicket(decodedText);
                },
                (errorMessage) => {}
            ).then(() => {
                isScanning = true;
                $('#camera-status').hide();
                $('#btn-start-cam').hide();
                $('#btn-stop-cam').show();

                const videoEl = document.querySelector('#reader video');
                if (videoEl) {
                    videoEl.style.setProperty('transform', 'scaleX(-1)', 'important');
                }
            }).catch((err) => {
                isScanning = false;
                $('#camera-status').html(
                    `Kamera tidak aktif / diblokir browser. Anda bisa langsung ketik kode di atas.`).show();
            });
        }

        // Fungsi mematikan kamera
        function stopCamera() {
            if (isScanning) {
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    $('#camera-status').text('Kamera dimatikan.').show();
                    $('#btn-stop-cam').hide();
                    $('#btn-start-cam').show();
                });
            }
        }

        // Jalankan kamera otomatis
        $(document).ready(function() {
            startCamera();
        });

        $('#btn-start-cam').on('click', startCamera);
        $('#btn-stop-cam').on('click', stopCamera);

        // Submit form input kode manual
        $('#scan-form').on('submit', function(e) {
            e.preventDefault();
            let code = $('#ticket_code').val().trim();
            if (code) {
                processTicket(code);
            }
        });
    </script>
</body>

</html>

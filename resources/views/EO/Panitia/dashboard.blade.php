<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Panitia - M!VENT</title>
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

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-card {
            background: #FFFFFF;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
            padding: 40px 32px;
            text-align: center;
            margin-bottom: 30px;
        }

        .avatar-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #E0F2FE;
            color: #0284C7;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 16px;
        }

        .welcome-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .welcome-subtitle {
            font-size: 15px;
            color: var(--text-muted);
            max-width: 500px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .action-card {
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 28px 24px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .action-card:hover {
            transform: translateY(-2px);
            border-color: #CBD5E1;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        }

        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .action-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .action-desc {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .btn-action {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-cyan {
            background-color: var(--primary);
            color: #0C4A6E;
        }

        .btn-cyan:hover {
            background-color: var(--primary-dark);
            color: #FFFFFF;
        }

        .btn-logout {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        .btn-logout:hover {
            background-color: #EF4444;
            color: #FFFFFF;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="#" class="brand-logo">M<span>!</span>VENT</a>
        <div class="user-tag">
            <span style="width: 8px; height: 8px; border-radius: 50%; background: #10B981; display: inline-block;"></span>
            {{ auth()->user()->name ?? 'Panitia Event' }} (Panitia)
        </div>
    </nav>

    <div class="container">
        <div class="welcome-card">
            <div class="avatar-circle">👋</div>
            <h1 class="welcome-title">Halo, {{ auth()->user()->name ?? 'Panitia' }}!</h1>
            <p class="welcome-subtitle">
                Selamat datang di Panel Kepanitiaan M!VENT. Anda dapat melakukan validasi e-tiket pengunjung di lokasi gerbang masuk event.
            </p>
        </div>

        <div class="action-grid">
            <!-- Scan Tiket Card -->
            <div class="action-card">
                <div>
                    <div class="action-icon" style="background: #E0F2FE; color: #0284C7;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <h3 class="action-title">Scan Tiket Peserta</h3>
                    <p class="action-desc">Validasi kehadiran peserta event di pintu masuk (gate) menggunakan scanner kamera atau kode order.</p>
                </div>
                <button type="button" class="btn-action btn-cyan" onclick="window.location.href='{{ route('panitia.scan.index') }}'">
                    Buka Scanner QR
                </button>
            </div>

            <!-- Logout Card -->
            <div class="action-card">
                <div>
                    <div class="action-icon" style="background: #FEE2E2; color: #DC2626;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <h3 class="action-title">Akhiri Sesi Panitia</h3>
                    <p class="action-desc">Keluar dari panel kepanitiaan jika giliran tugas Anda hari ini telah selesai.</p>
                </div>
                <form action="{{ route('panitia.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-action btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar dari panel panitia?');">
                        Logout Sesi
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>

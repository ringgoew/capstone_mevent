<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard EO - Event Management</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f6f8;
            color: #222;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            height: 100vh;
            background-color: #111;
            color: white;
            padding: 25px 18px;
        }

        .logo {
            font-size: 21px;
            font-weight: bold;
            margin-bottom: 35px;
            padding-left: 10px;
        }

        .sidebar a {
            display: block;
            color: #ddd;
            text-decoration: none;
            padding: 11px 13px;
            margin-bottom: 4px;
            border-radius: 7px;
            font-size: 14px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #333;
            color: white;
        }

        .menu-title {
            color: #888;
            font-size: 11px;
            margin: 20px 10px 8px;
            text-transform: uppercase;
        }

        /* MAIN */
        .main {
            margin-left: 240px;
            padding: 35px 45px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .topbar h1 {
            font-size: 28px;
        }

        .subtitle {
            color: #777;
            margin-top: 7px;
            font-size: 14px;
        }

        .profile {
            background-color: white;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
        }

        /* STATISTIK */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            padding: 22px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .stat-card p {
            color: #777;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .stat-card h2 {
            font-size: 24px;
        }

        /* SECTION */
        .section {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h2 {
            font-size: 20px;
        }

        .btn {
            background-color: #111;
            color: white;
            padding: 10px 15px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 13px;
        }

        /* EVENT */
        .event-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 0;
            border-bottom: 1px solid #eee;
        }

        .event-item:last-child {
            border-bottom: none;
        }

        .event-info h3 {
            margin-bottom: 8px;
        }

        .event-info p {
            color: #777;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .event-status {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 6px 11px;
            border-radius: 15px;
            font-size: 12px;
        }

        .detail-btn {
            display: inline-block;
            margin-top: 7px;
            color: #222;
            font-size: 13px;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="logo">
            Event Management
        </div>

        <a href="{{ route('eo.dashboard') }}" class="active">
            Dashboard
        </a>

        <a href="#">
            Event Saya
        </a>

        <a href="#">
            Buat Event
        </a>

        <a href="#">
            Tiket & Kuota
        </a>

        <a href="#">
            Penjualan Tiket
        </a>

        <a href="#">
            Data Peserta
        </a>

        <a href="#">
            Laporan
        </a>

        <a href="#">
            Payout
        </a>

        <div class="menu-title">
            Akun
        </div>

        <a href="#">
            Profil & Dokumen
        </a>

        <a href="{{ route('home') }}">
            Logout
        </a>

    </aside>


    <!-- MAIN CONTENT -->
    <main class="main">

        <!-- TOPBAR -->
        <div class="topbar">

            <div>
                <h1>Dashboard EO</h1>

                <p class="subtitle">
                    Kelola event dan pantau aktivitas event kamu.
                </p>
            </div>

            <div class="profile">
                EO Event POOKIEMAX
            </div>

        </div>


        <!-- STATISTIK -->
        <div class="stats">

            <div class="stat-card">
                <p>Total Event</p>
                <h2>{{ $stats['total_event'] }}</h2>
            </div>

            <div class="stat-card">
                <p>Tiket Terjual</p>
                <h2>{{ $stats['tiket_terjual'] }}</h2>
            </div>

            <div class="stat-card">
                <p>Total Pendapatan</p>
                <h2>
                    Rp{{ number_format($stats['pendapatan'], 0, ',', '.') }}
                </h2>
            </div>

            <div class="stat-card">
                <p>Peserta Hadir</p>
                <h2>{{ $stats['peserta_hadir'] }}</h2>
            </div>

        </div>


        <!-- EVENT SAYA -->
        <div class="section">

            <div class="section-header">

                <h2>Event Saya</h2>

                <a href="#" class="btn">
                    + Buat Event
                </a>

            </div>


            @foreach ($events as $event)

                <div class="event-item">

                    <div class="event-info">

                        <h3>{{ $event['nama'] }}</h3>

                        <p>
                            📅 {{ $event['tanggal'] }}
                            &nbsp; • &nbsp;
                            📍 {{ $event['lokasi'] }}
                        </p>

                        <p>
                            🎟 {{ $event['tiket_terjual'] }} tiket terjual
                        </p>

                        <a href="#" class="detail-btn">
                            Lihat Detail →
                        </a>

                    </div>

                    <div class="event-status">
                        {{ $event['status'] }}
                    </div>

                </div>

            @endforeach

        </div>

    </main>

</body>
</html>
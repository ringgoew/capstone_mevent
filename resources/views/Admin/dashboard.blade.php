<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin</title>


    <style>
        * {
            margin: 0;
            padding: 0;

            box-sizing: border-box;

            font-family: Arial, sans-serif;
        }


        body {
            background: #f5f5f5;

            color: #222;
        }


        /* NAVBAR */

        nav {
            height: 70px;

            background: white;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 50px;

            box-shadow:
                0 2px 8px rgba(0, 0, 0, 0.08);
        }


        .logo {
            font-size: 24px;

            font-weight: bold;
        }


        .admin-area {
            display: flex;

            align-items: center;

            gap: 15px;
        }


        .admin-name {
            font-size: 14px;

            color: #555;
        }


        .logout {
            padding: 9px 15px;

            background: #111;

            color: white;

            border: none;

            border-radius: 7px;

            cursor: pointer;
        }


        .logout:hover {
            background: #333;
        }


        /* CONTENT */

        .container {
            width: 90%;

            max-width: 1200px;

            margin: 40px auto;
        }


        .header {
            margin-bottom: 30px;
        }


        .header h1 {
            margin-bottom: 8px;
        }


        .header p {
            color: #777;
        }


        /* ALERT */

        .success {
            background: #e8f7ed;

            color: #20733a;

            padding: 13px 15px;

            border-radius: 8px;

            margin-bottom: 25px;
        }


        /* STATISTIC */

        .stats {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 20px;

            margin-bottom: 30px;
        }


        .stat-card {
            background: white;

            padding: 22px;

            border-radius: 12px;

            box-shadow:
                0 3px 12px rgba(0, 0, 0, 0.06);
        }


        .stat-title {
            color: #777;

            font-size: 14px;

            margin-bottom: 10px;
        }


        .stat-number {
            font-size: 30px;

            font-weight: bold;
        }


        /* MENU */

        .menu-container {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;
        }


        .menu-card {
            background: white;

            padding: 25px;

            border-radius: 12px;

            box-shadow:
                0 3px 12px rgba(0, 0, 0, 0.06);
        }


        .menu-card h3 {
            margin-bottom: 8px;
        }


        .menu-card p {
            color: #777;

            font-size: 14px;

            line-height: 1.5;
        }


        @media (max-width: 900px) {

            .stats {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .menu-container {
                grid-template-columns:
                    1fr;
            }

        }


        @media (max-width: 600px) {

            nav {
                padding: 0 20px;
            }

            .admin-name {
                display: none;
            }

            .stats {
                grid-template-columns:
                    1fr;
            }

        }
    </style>

</head>


<body>


    <!-- NAVBAR -->

    <nav>


        <div class="logo">
            Event Management
        </div>


        <div class="admin-area">

            <span class="admin-name">

                {{ session('admin_name') }}

            </span>


            <form action="{{ route('admin.logout') }}" method="POST">

                @csrf

                <button type="submit" class="logout">
                    Logout
                </button>

            </form>

        </div>


    </nav>



    <!-- CONTENT -->

    <div class="container">


        <div class="header">

            <h1>
                Dashboard Admin
            </h1>

            <p>
                Selamat datang di halaman administrator.
            </p>

        </div>


        @if (session('success'))
            <div class="success">

                {{ session('success') }}

            </div>
        @endif


        <!-- STATISTIC -->

        <div class="stats">


            <div class="stat-card">

                <div class="stat-title">
                    Total Event
                </div>

                <div class="stat-number">
                    {{ $data['total_event'] }}
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-title">
                    Event Pending
                </div>

                <div class="stat-number">
                    {{ $data['event_pending'] }}
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-title">
                    Total EO
                </div>

                <div class="stat-number">
                    {{ $data['total_eo'] }}
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-title">
                    Tiket Terjual
                </div>

                <div class="stat-number">
                    {{ $data['total_tiket'] }}
                </div>

            </div>


        </div>


        <!-- MENU ADMIN -->

        <div class="menu-container">


            <div class="menu-card">

                <h3>
                    Verifikasi EO
                </h3>

                <p>
                    Admin dapat melakukan pemeriksaan
                    terhadap pendaftaran Event Organizer.
                </p>

            </div>


            <div class="menu-card">

                <h3>
                    Verifikasi Event
                </h3>

                <p>
                    Admin dapat memeriksa event
                    sebelum event dipublikasikan.
                </p>

            </div>


            <div class="menu-card">

                <h3>
                    Laporan
                </h3>

                <p>
                    Admin dapat melihat informasi
                    dan laporan dari sistem.
                </p>

            </div>


        </div>


    </div>


</body>

</html>

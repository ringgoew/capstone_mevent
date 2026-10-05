<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Event Management</title>

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

        /* NAVBAR */
        nav {
            height: 70px;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 60px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .menu {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .menu a {
            text-decoration: none;
            color: #333;
            font-size: 15px;
        }

        .btn-eo {
            border: 1px solid #333;
            padding: 10px 16px;
            border-radius: 8px;
        }

        /* HERO */
        .hero {
            padding: 70px 60px;
            background: linear-gradient(135deg, #f0f0f0, #ffffff);
        }

        .hero h1 {
            font-size: 45px;
            max-width: 650px;
            margin-bottom: 15px;
        }

        .hero p {
            color: #666;
            font-size: 17px;
            margin-bottom: 25px;
        }

        .search {
            width: 500px;
            max-width: 100%;
            padding: 15px 18px;
            border: 1px solid #ddd;
            border-radius: 10px;
            outline: none;
        }

        /* EVENT */
        .event-section {
            padding: 45px 60px;
        }

        .event-section h2 {
            margin-bottom: 25px;
        }

        .event-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
        }

        .event-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .event-image {
            height: 160px;
            overflow: hidden;
        }

        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .event-content {
            padding: 18px;
        }

        .event-content h3 {
            margin-bottom: 10px;
        }

        .event-content p {
            color: #666;
            font-size: 14px;
            margin-bottom: 7px;
        }

        .price {
            font-weight: bold;
            color: #111 !important;
            margin-top: 12px;
        }

        .btn-detail {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background-color: #111;
            color: white;
            text-decoration: none;
            border-radius: 7px;
            font-size: 14px;
        }

        /* FOOTER */
        footer {
            margin-top: 40px;
            padding: 25px 60px;
            background-color: #111;
            color: white;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav>
        <div class="logo">
            Event POOKIEMAX
        </div>

        <div class="menu">

                <a href="{{ route('eo.login') }}" class="btn-masuk">
            Masuk
        </a>

            <a href="{{ route('eo.register') }}" class="btn-eo">
                Daftar sebagai EO
            </a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <h1>
            Temukan Event Menarik di Sekitarmu
        </h1>

        <p>
            Cari dan beli tiket event favoritmu dengan mudah.
        </p>

        <input
            type="text"
            class="search"
            placeholder="Cari event..."
        >
    </section>

    <!-- EVENT -->
    <section class="event-section">

        <h2>Event Terbaru</h2>

        <div class="event-container">

            @foreach ($events as $event)

                <div class="event-card">

                    <div class="event-image">
                        <img
                            src="{{ asset('assets/img/events/' . $event['foto']) }}"
                            alt="{{ $event['nama'] }}"
                        >
                    </div>

                    <div class="event-content">

                        <h3>
                            {{ $event['nama'] }}
                        </h3>

                        <p>
                            📅 {{ $event['tanggal'] }}
                        </p>

                        <p>
                            📍 {{ $event['location'] }}
                        </p>

                        <p class="price">
                            {{ $event['harga'] }}
                        </p>

                        <a href="#" class="btn-detail">
                            Lihat Detail
                        </a>

                    </div>

                </div>

            @endforeach

        </div>

    </section>

    <!-- FOOTER -->
    <footer>
        © 2026 Event Management
    </footer>

</body>
</html>
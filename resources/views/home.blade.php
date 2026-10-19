@extends('layouts.main')
@section('title', 'Home - M!VENT')
@section('content')

    <section class="hero">
        <h1>
            Temukan Event Menarik di Sekitarmu
        </h1>
        <p>
            Cari dan beli tiket event favoritmu dengan mudah.
        </p>

        <!-- Form Pencarian & Filter Sesuai Alur Diagram -->
        <form action="#" method="GET" class="search-form">
            <input
                type="text"
                name="keyword"
                class="search"
                placeholder="Masukkan kata kunci event..."
                value="{{ request('keyword') }}"
            >
            <select name="category" class="filter-select">
                <option value="">Semua Kategori</option>
                <option value="konser">Konser Musik</option>
                <option value="seminar">Seminar & Workshop</option>
                <option value="festival">Festival</option>
            </select>
            <button type="submit" class="btn-search">Cari</button>
        </form>
    </section>

    <!-- EVENT SECTION -->
    <section class="event-section">
        <h2>Event Terbaru</h2>
        <div class="event-container">
            @forelse ($events as $event)
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
                            Rp {{ number_format($event['harga'], 0, ',', '.') }}
                        </p>

                        <!-- Menuju Detail Event sesuai Activity Diagram -->
                        <a href="#" class="btn-detail">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <p>Tidak ada event yang ditemukan.</p>
            @endforelse
        </div>
    </section>
@endsection
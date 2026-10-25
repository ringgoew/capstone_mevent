@extends('layouts.main')

@section('title', 'Home - M!VENT')

@section('content')

<section class="hero">
    <h1>Temukan Event Menarik di Sekitarmu</h1>

    <p>
        Cari dan beli tiket event favoritmu dengan mudah.
    </p>

    <form action="{{ route('home') }}" method="GET" class="search-form">
        <input
            type="text"
            name="keyword"
            class="search"
            placeholder="Masukkan kata kunci event..."
            value="{{ request('keyword') }}"
        >

        <select name="category" class="filter-select">
            <option value="">Semua Kategori</option>

            @foreach ($event_categories as $category)
                <option
                    value="{{ $category->id }}"
                    {{ request('category') == $category->id ? 'selected' : '' }}
                >
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-search">
            Cari
        </button>
    </form>
</section>

<section class="event-section">
    <h2>Event Terbaru</h2>

    <div class="event-container">
        @forelse ($events as $event)
            <div class="event-card">
                <div class="event-image">
                    @if ($event->foto)
<img
    src="{{ asset('assets/img/events/' . $event->foto) }}"
    alt="{{ $event->nama }}"
>                    @else
                        <p>Gambar belum tersedia</p>
                    @endif
                </div>

                <div class="event-content">
                    <h3>{{ $event->nama }}</h3>

                    <p>
                        📅
                        {{ $event->tanggal
                            ? \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d F Y')
                            : '-' }}
                    </p>

                    <p>📍 {{ $event->location ?? '-' }}</p>

                    <p class="price">
                        @if ($event->harga !== null)
                            Rp {{ number_format($event->harga, 0, ',', '.') }}
                        @else
                            Harga belum tersedia
                        @endif
                    </p>

                    <a
                        href="{{ route('events.show', $event->slug) }}"
                        class="btn-detail"
                    >
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
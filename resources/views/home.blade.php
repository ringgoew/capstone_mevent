@extends('layouts.main')
@section('title', 'M!VENT — Temukan Event Terbaik')
@section('content')

<section class="hero">
    <div class="hero-grid-pattern" aria-hidden="true"></div>

    <div class="hero-content">

        <h1>Temukan Event <span class="hero-highlight">Menarik</span> di Sekitarmu</h1>
        <p>Cari, filter kategori, dan amankan tiket event favoritmu — mulai dari konser musik, seminar, hingga festival.</p>

        {{-- Form Pencarian & Filter Kategori (Logika & Input Name Tetap Sama) --}}
        <form action="{{ route('home') }}" method="GET" class="search-form">
            <div class="search-input-box">
                <svg class="search-input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input
                    type="text"
                    name="keyword"
                    class="search"
                    placeholder="Cari nama event, lokasi, atau penyelenggara..."
                    value="{{ request('keyword') }}">
            </div>

            <span class="search-divider" aria-hidden="true"></span>

            <div class="filter-select-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                <select name="category" class="filter-select" aria-label="Pilih Kategori Event">
                    <option value="">Semua Kategori</option>
                    @foreach ($event_categories as $category)
                    <option value="{{ $category->id }}" {{ (string) request('category') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-search">
                Cari Event
            </button>
        </form>

        {{-- Quick Category Filter Pills --}}
        @if ($event_categories->count() > 0)
        <div class="category-pills">
            <a href="{{ route('home', array_filter(['keyword' => request('keyword')])) }}"
                class="category-pill {{ !request('category') ? 'active' : '' }}">
                Semua Event
            </a>
            @foreach ($event_categories as $category)
            <a href="{{ route('home', array_filter(['category' => $category->id, 'keyword' => request('keyword')])) }}"
                class="category-pill {{ (string) request('category') === (string) $category->id ? 'active' : '' }}">
                {{ $category->name }}
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- Explore Categories (Figma Circular Grid terhubung langsung ke $event_categories) --}}
@if ($event_categories->count() > 0)
<section id="kategori-event" class="explore-categories-section">
    <h2>Explore Categories</h2>
    <div class="explore-categories-grid">
        @foreach ($event_categories as $category)
        <a href="{{ route('home', array_filter(['category' => $category->id, 'keyword' => request('keyword')])) }}#katalog-event"
            class="explore-cat-card {{ (string) request('category') === (string) $category->id ? 'active' : '' }}">
            <div class="explore-cat-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M20 12c0-1.1.9-2 2-2V6c0-1.1-.9-2-2-2H4c-1.1 0-1.99.9-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2z"></path>
                </svg>
            </div>
            <span class="explore-cat-name">{{ $category->name }}</span>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- Katalog Event --}}
<section id="katalog-event" class="event-section">
    @if (session('error'))
    <div class="alert-box alert-error">{{ session('error') }}</div>
    @endif
    @if (session('success'))
    <div class="alert-box alert-success">{{ session('success') }}</div>
    @endif

    <div class="event-section-header">
        <div>
            <h2>
                @if (request('keyword') || request('category'))
                Hasil Pencarian Event
                @else
                Popular Events
                @endif
            </h2>
            <p class="event-section-subtitle">
                Menampilkan {{ $events->count() }} event aktif siap dipesan
            </p>
        </div>

        @if (request('keyword') || request('category'))
        <a href="{{ route('home') }}" class="clear-filter">
            ✕ Reset Filter
        </a>
        @endif
    </div>

    <div class="event-container">
        @forelse ($events as $index => $event)
        @php
        $eventTitle = $event->title ?? $event->nama ?? 'Event Tanpa Judul';
        $eventDate = $event->start_time ?? $event->tanggal ?? null;
        $parsedDate = $eventDate ? \Carbon\Carbon::parse($eventDate) : null;

        $eventLocation = collect([$event->venue_name ?? null, $event->city ?? null])->filter()->implode(', ');
        if (empty($eventLocation)) {
        $eventLocation = $event->location ?? 'Lokasi menyusul';
        }

        $imagePath = null;
        if (!empty($event->banner_image)) {
        $imagePath = \Illuminate\Support\Str::startsWith($event->banner_image, ['http://', 'https://'])
        ? $event->banner_image
        : asset('storage/' . $event->banner_image);
        } elseif (!empty($event->foto)) {
        $imagePath = asset('assets/img/events/' . $event->foto);
        }

        $categoryName = $event->category->name
        ?? $event->category_name
        ?? ($event_categories->firstWhere('id', $event->category_id)->name ?? null);

        $minPrice = $event->tickets_min_price
        ?? (method_exists($event, 'relationLoaded') && $event->relationLoaded('tickets') ? $event->tickets->min('price') : null)
        ?? $event->harga
        ?? null;
        @endphp

        <article class="event-card reveal-on-scroll" data-delay="{{ min($index * 60, 360) }}">
            <div class="event-image">
                @if ($minPrice !== null && (float) $minPrice === 0.0)
                <span class="event-free-badge">FREE</span>
                @endif

                <span class="event-star-badge" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                    </svg>
                </span>

                @if ($categoryName)
                <span class="event-card-badge">{{ $categoryName }}</span>
                @endif

                @if ($imagePath)
                <a href="{{ route('events.show', $event->slug) }}">
                    <img src="{{ $imagePath }}" alt="{{ $eventTitle }}" loading="lazy">
                </a>
                @else
                <a href="{{ route('events.show', $event->slug) }}" class="event-image-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="3" ry="3"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    <span>Poster Event</span>
                </a>
                @endif
            </div>

            <div class="event-card-body">
                {{-- Kolom Tanggal ala Figma (Bulan Ungu #4539B4 + Tanggal) --}}
                <div class="event-date-box">
                    <div class="event-date-month">{{ $parsedDate ? $parsedDate->translatedFormat('M') : 'TBA' }}</div>
                    <div class="event-date-day">{{ $parsedDate ? $parsedDate->format('d') : '--' }}</div>
                </div>

                <div class="event-content">
                    <h3>
                        <a href="{{ route('events.show', $event->slug) }}">{{ $eventTitle }}</a>
                    </h3>

                    <div class="event-meta-list">
                        <p class="event-meta-date">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <span>{{ $parsedDate ? $parsedDate->translatedFormat('d F Y, H:i') . ' WIB' : 'Jadwal menyusul' }}</span>
                        </p>

                        <p class="event-meta-loc">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span>{{ $eventLocation }}</span>
                        </p>
                    </div>

                    <div class="event-card-footer">
                        <div class="price">
                            @if ($minPrice !== null && (float) $minPrice > 0)
                            <span class="price-prefix">Mulai dari</span>
                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                            @elseif ($minPrice !== null && (float) $minPrice === 0.0)
                            <span class="price-free">Gratis</span>
                            @else
                            <span class="price-prefix">Tiket</span>
                            Tersedia
                            @endif
                        </div>

                        <a href="{{ route('events.show', $event->slug) }}" class="btn-detail">
                            Lihat Detail <span class="arrow-icon">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </article>
        @empty
        <div class="events-empty">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="margin: 0 auto;">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <h3>Tidak ada event yang ditemukan</h3>
            <p>Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
            @if (request('keyword') || request('category'))
            <a href="{{ route('home') }}" class="btn-detail" style="margin-top: 16px;">Lihat Semua Event</a>
            @endif
        </div>
        @endforelse
    </div>
</section>

{{-- Curated Banner (Figma Section) --}}
<section class="curated-section">
    <div class="curated-banner">
        <h2>Events specially curated for you!</h2>
        <p>Temukan rekomendasi acara terbaik sesuai minatmu dan amankan tiketnya sebelum kehabisan.</p>
        <a href="#katalog-event" class="btn-curated-cta">
            Jelajahi Sekarang <span>→</span>
        </a>
    </div>
</section>

{{-- Create Event with M!VENT Banner (Figma EO CTA) --}}
<section class="eo-cta-section">
    <div class="eo-cta-inner">
        <div>
            <h2>Create an event with M!VENT</h2>
            <p>Punya konser, seminar, atau festival? Bermitra dengan kami dan kelola penjualan tiketmu dengan mudah.</p>
        </div>
        <a href="{{ route('eo.register') }}" class="btn-eo-cta">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <rect x="3" y="4" width="18" height="18" rx="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
                <line x1="12" y1="14" x2="12" y2="18" />
                <line x1="10" y1="16" x2="14" y2="16" />
            </svg>
            Daftar sebagai EO
        </a>
    </div>
</section>

{{-- Newsletter Bar (Figma Yellow Section) --}}
<section class="newsletter-section">
    <div class="newsletter-inner">
        <div class="newsletter-text">
            <h3>Subscribe to our Newsletter</h3>
            <p>Dapatkan informasi event terbaru langsung ke email kamu setiap minggunya.</p>
        </div>
        <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Terima kasih telah berlangganan info event M!VENT!'); this.reset();">
            <input type="email" placeholder="Masukkan alamat email kamu..." required>
            <button type="submit">Subscribe</button>
        </form>
    </div>
</section>

@push('scripts')
<script>
    document.querySelectorAll('.reveal-on-scroll[data-delay]').forEach((card) => {
        card.style.transitionDelay = `${card.dataset.delay}ms`;
    });
</script>
@endpush

@endsection
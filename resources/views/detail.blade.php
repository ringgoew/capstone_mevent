@extends('layouts.main')

@section('title', $event->title . ' - M!VENT')

@section('content')
<div class="detail-page-wrapper">
    <div class="detail-container">
        <!-- Tombol Kembali -->
        <a href="{{ route('home') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Home
        </a>

        <!-- Card Utama Event -->
        <div class="event-hero-card">
            @if ($event->banner_image)
            <div class="banner-wrapper">
                <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" class="banner-img">
            </div>
            @endif

            <div class="event-hero-body">
                @if ($category)
                <span class="category-badge">{{ $category->name }}</span>
                @endif

                <h1 class="event-title">{{ $event->title }}</h1>

                <p class="event-description">{{ $event->description }}</p>

                <div class="event-meta-grid">
                    <div class="meta-item">
                        <div class="meta-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <div>
                            <span class="meta-label">Tanggal & Waktu</span>
                            <strong class="meta-value">{{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('d F Y, H:i') }} WIB</strong>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <div>
                            <span class="meta-label">Lokasi Tempat</span>
                            <strong class="meta-value">{{ $event->venue_name }}, {{ $event->city }}</strong>
                        </div>
                    </div>

                    <div class="meta-item meta-full">
                        <div class="meta-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10z"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="meta-label">Alamat Lengkap</span>
                            <strong class="meta-value">{{ $event->address }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Tiket -->
        <div class="tickets-section">
            <h2 class="section-title">Pilihan Tiket</h2>

            <div class="tickets-grid">
                @forelse ($tickets as $ticket)
                <div class="ticket-card">
                    <div class="ticket-info">
                        <h3 class="ticket-name">{{ $ticket->name }}</h3>
                        <p class="ticket-quota">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Tersedia {{ $ticket->quota }} tiket
                        </p>
                    </div>
                    <div class="ticket-action">
                        <span class="ticket-price">
                            @if ($ticket->price > 0)
                            Rp {{ number_format($ticket->price, 0, ',', '.') }}
                            @else
                            Gratis
                            @endif
                        </span>
                        <button class="btn-buy-ticket">Beli Tiket</button>
                    </div>
                </div>
                @empty
                <div class="empty-tickets">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                        <line x1="6" y1="12" x2="10" y2="12"></line>
                    </svg>
                    <p>Belum ada tiket yang tersedia untuk event ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
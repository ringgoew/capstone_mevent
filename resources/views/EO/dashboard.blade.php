@extends('layouts.eo')

@section('title', 'Dashboard EO — M!VENT')

@section('content')
@php
$eventCollection = collect($events ?? []);
$totalEventsCount = $eventCollection->count();

$publishedCount = $eventCollection->filter(fn($e) => strtolower($e->status ?? 'draft') === 'published')->count();
$pendingCount = $eventCollection->filter(fn($e) => strtolower($e->status ?? 'draft') === 'pending')->count();
$draftCount = $eventCollection->filter(fn($e) => strtolower($e->status ?? 'draft') === 'draft')->count();
$rejectedCount = $eventCollection->filter(fn($e) => in_array(strtolower($e->status ?? 'draft'), ['rejected', 'cancelled']))->count();

$totalTicketsSold = (int) ($stats['tiket_terjual'] ?? 0);
$totalRevenue = (float) ($stats['pendapatan'] ?? 0);
$totalAttendees = (int) ($stats['peserta_hadir'] ?? 0);
$attendanceRate = $totalTicketsSold > 0 ? min(100, round(($totalAttendees / $totalTicketsSold) * 100)) : 0;

$maxEventTickets = max(1, (int) ($eventCollection->max('tiket_terjual') ?? 1));
$topSalesEvents = $eventCollection->sortByDesc(fn($e) => (int) ($e->tiket_terjual ?? 0))->take(4)->values();
@endphp

<div class="eod-dashboard">

    <!-- TOPBAR HEADER -->
    <div class="eod-topbar">
        <div class="eod-topbar-title">
            <span class="eod-eyebrow">EVENT ORGANIZER WORKSPACE</span>
            <h1>Dashboard EO</h1>
            <p class="eod-subtitle">Kelola event dan pantau aktivitas penjualan tiket kamu secara real-time.</p>
        </div>

        <div class="eod-topbar-actions">
            <div class="eod-profile-pill" title="Akun Event Organizer Aktif">
                <span class="eod-profile-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                    </svg>
                </span>
                <span class="eod-profile-name">{{ session('eo_nama', 'Event Organizer') }}</span>
            </div>

            <a href="{{ route('eo.events.create') }}" class="eod-btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Buat Event</span>
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI SESI -->
    @if (session('success'))
    <div class="eod-alert eod-alert-success" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="eod-alert eod-alert-error" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- 4 KARTU STATISTIK UTAMA -->
    <div class="eod-stats-grid">
        <div class="eod-stat-card">
            <div class="eod-stat-top">
                <span class="eod-stat-label">Total Event</span>
                <span class="eod-stat-icon eod-icon-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </span>
            </div>
            <div class="eod-stat-value">{{ $stats['total_event'] ?? 0 }}</div>
            <div class="eod-stat-foot">
                <span class="eod-stat-pill eod-pill-purple">{{ $publishedCount }} Tayang</span>
                <span class="eod-stat-hint">dari total katalog EO</span>
            </div>
        </div>

        <div class="eod-stat-card">
            <div class="eod-stat-top">
                <span class="eod-stat-label">Tiket Terjual</span>
                <span class="eod-stat-icon eod-icon-yellow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                        <path d="M13 5v2"></path>
                        <path d="M13 17v2"></path>
                        <path d="M13 11v2"></path>
                    </svg>
                </span>
            </div>
            <div class="eod-stat-value">{{ number_format($totalTicketsSold, 0, ',', '.') }}</div>
            <div class="eod-stat-foot">
                <span class="eod-stat-pill eod-pill-navy">Aktif</span>
                <span class="eod-stat-hint">akumulasi seluruh tiket</span>
            </div>
        </div>

        <div class="eod-stat-card">
            <div class="eod-stat-top">
                <span class="eod-stat-label">Total Pendapatan</span>
                <span class="eod-stat-icon eod-icon-green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </span>
            </div>
            <div class="eod-stat-value eod-stat-currency">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="eod-stat-foot">
                <span class="eod-stat-pill eod-pill-green">Gross Sales</span>
                <span class="eod-stat-hint">pendapatan tiket tercatat</span>
            </div>
        </div>

        <div class="eod-stat-card">
            <div class="eod-stat-top">
                <span class="eod-stat-label">Peserta Hadir</span>
                <span class="eod-stat-icon eod-icon-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </span>
            </div>
            <div class="eod-stat-value">{{ number_format($totalAttendees, 0, ',', '.') }}</div>
            <div class="eod-stat-foot">
                <span class="eod-stat-pill eod-pill-blue">{{ $attendanceRate }}%</span>
                <span class="eod-stat-hint">rasio check-in peserta</span>
            </div>
        </div>
    </div>

    <!-- PANEL ANALITIK & KENDALI CEPAT (2 KOLOM) -->
    <div class="eod-overview-grid">

        <!-- Kolom Kiri: Performa Penjualan Tiket per Event -->
        <div class="eod-card eod-perf-card">
            <div class="eod-card-head">
                <div>
                    <h2>Performa Penjualan Tiket</h2>
                    <p>Distribusi tiket terjual pada event teratas milikmu</p>
                </div>
                <span class="eod-badge-outline">{{ $totalEventsCount }} Event Terdaftar</span>
            </div>

            <div class="eod-mini-metrics">
                <div class="eod-mini-box">
                    <span class="eod-mini-label">Tiket Terjual</span>
                    <strong>{{ number_format($totalTicketsSold, 0, ',', '.') }}</strong>
                </div>
                <div class="eod-mini-box">
                    <span class="eod-mini-label">Total Pendapatan</span>
                    <strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong>
                </div>
                <div class="eod-mini-box">
                    <span class="eod-mini-label">Tingkat Kehadiran</span>
                    <strong>{{ $attendanceRate }}%</strong>
                </div>
            </div>

            <div class="eod-ticket-divider" aria-hidden="true"></div>

            @if ($topSalesEvents->isNotEmpty())
            <div class="eod-progress-list">
                @php
                $barColors = ['#2B293D', '#4539B4', '#FFE047', '#287921'];
                @endphp
                @foreach ($topSalesEvents as $idx => $topEvent)
                @php
                $soldCount = (int) ($topEvent->tiket_terjual ?? 0);
                $pct = $maxEventTickets > 0 ? round(($soldCount / $maxEventTickets) * 100) : 0;
                $displayPct = $soldCount > 0 ? max(8, $pct) : 0;
                $barColor = $barColors[$idx % count($barColors)];
                @endphp
                <div class="eod-progress-item">
                    <div class="eod-progress-meta">
                        <a href="{{ route('eo.events.show', $topEvent->id) }}" class="eod-progress-title">
                            {{ $topEvent->title ?? ($topEvent->nama ?? 'Event tanpa judul') }}
                        </a>
                        <span class="eod-progress-count">{{ number_format($soldCount, 0, ',', '.') }} tiket</span>
                    </div>
                    <div class="eod-progress-track">
                        <div class="eod-progress-fill" data-width="{{ $displayPct }}" data-color="{{ $barColor }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="eod-empty-mini">
                <p>Belum ada data penjualan tiket yang ditampilkan. Mulai publikasikan event pertamamu!</p>
            </div>
            @endif
        </div>

        <!-- Kolom Kanan: Kartu Deep Navy Status & Aksi Cepat -->
        <div class="eod-navy-card">
            <div class="eod-navy-head">
                <div>
                    <span class="eod-navy-tag">RINGKASAN STATUS</span>
                    <h2>Status & Aksi Cepat</h2>
                </div>
                <span class="eod-navy-total">{{ $totalEventsCount }}</span>
            </div>

            <div class="eod-status-breakdown">
                <div class="eod-status-row">
                    <div class="eod-status-row-left">
                        <span class="eod-dot eod-dot-green"></span>
                        <span>Published (Tayang)</span>
                    </div>
                    <strong>{{ $publishedCount }}</strong>
                </div>

                <div class="eod-status-row">
                    <div class="eod-status-row-left">
                        <span class="eod-dot eod-dot-yellow"></span>
                        <span>Pending Review</span>
                    </div>
                    <strong>{{ $pendingCount }}</strong>
                </div>

                <div class="eod-status-row">
                    <div class="eod-status-row-left">
                        <span class="eod-dot eod-dot-muted"></span>
                        <span>Draft Tersimpan</span>
                    </div>
                    <strong>{{ $draftCount }}</strong>
                </div>

                @if ($rejectedCount > 0)
                <div class="eod-status-row">
                    <div class="eod-status-row-left">
                        <span class="eod-dot eod-dot-red"></span>
                        <span>Rejected / Cancelled</span>
                    </div>
                    <strong>{{ $rejectedCount }}</strong>
                </div>
                @endif
            </div>

            <div class="eod-navy-actions">
                <a href="{{ route('eo.events.create') }}" class="eod-btn-yellow-full">
                    + Buat Event Baru
                </a>
            </div>
        </div>
    </div>

    <!-- SECTION DAFTAR EVENT SAYA -->
    <div class="eod-card eod-events-panel" id="eod-events-section">
        <div class="eod-events-header">
            <div class="eod-events-heading">
                <h2>Event Saya</h2>
                <span class="eod-count-badge">{{ $totalEventsCount }}</span>
            </div>

            <div class="eod-events-toolbar">
                <!-- Filter Pills -->
                <div class="eod-filter-group" role="tablist" aria-label="Filter status event">
                    <button type="button" class="eod-filter-pill is-active" data-filter="all">Semua</button>
                    <button type="button" class="eod-filter-pill" data-filter="published">Published</button>
                    <button type="button" class="eod-filter-pill" data-filter="pending">Pending</button>
                    <button type="button" class="eod-filter-pill" data-filter="draft">Draft</button>
                </div>

                <!-- Pencarian Sisi Klien -->
                <div class="eod-search-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="search" id="eodEventSearch" class="eod-search-input" placeholder="Cari judul atau lokasi event..." aria-label="Cari event">
                </div>
            </div>
        </div>

        <div class="eod-event-list" id="eodEventList">
            @forelse ($events as $event)
            @php
            $eventTitle = $event->title ?? ($event->nama ?? 'Event tanpa judul');
            $status = strtolower($event->status ?? 'draft');
            $statusClass = match ($status) {
            'published' => 'status-published',
            'pending' => 'status-pending',
            'rejected', 'cancelled' => 'status-rejected',
            default => 'status-draft',
            };
            $venueText = !empty($event->venue_name)
            ? $event->venue_name . (!empty($event->city) ? ', ' . $event->city : '')
            : ($event->lokasi ?? 'Lokasi belum diatur');
            $sold = (int) ($event->tiket_terjual ?? 0);
            $relBar = $maxEventTickets > 0 ? round(($sold / $maxEventTickets) * 100) : 0;
            $relBarWidth = $sold > 0 ? max(6, $relBar) : 0;
            @endphp

            <div class="eod-event-card"
                data-status="{{ $status }}"
                data-search="{{ strtolower($eventTitle . ' ' . $venueText) }}">

                <!-- Thumbnail Poster Event -->
                <div class="eod-event-thumb">
                    @if (!empty($event->banner_image) || !empty($event->foto))
                    <img src="{{ asset('storage/' . ($event->banner_image ?? $event->foto)) }}"
                        alt="{{ $eventTitle }}"
                        loading="lazy">
                    @else
                    <div class="eod-event-thumb-placeholder">
                        <span>M!</span>
                    </div>
                    @endif
                </div>

                <!-- Informasi Utama Event -->
                <div class="eod-event-body">
                    <div class="eod-event-title-row">
                        <h3>
                            <a href="{{ route('eo.events.show', $event->id) }}">{{ $eventTitle }}</a>
                        </h3>
                        <span class="event-status {{ $statusClass }} eod-status-pill">
                            {{ ucfirst($status) }}
                        </span>
                    </div>

                    <div class="eod-event-meta">
                        <span class="eod-meta-chip">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            @if (!empty($event->start_time))
                            {{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('d F Y, H:i') }}
                            @elseif (!empty($event->tanggal))
                            {{ $event->tanggal }}
                            @else
                            Jadwal belum diatur
                            @endif
                        </span>

                        <span class="eod-meta-chip">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            {{ $venueText }}
                        </span>
                    </div>

                    <!-- Mini Bar Penjualan Tiket -->
                    <div class="eod-event-sales-bar">
                        <span class="eod-sales-text">
                            <strong>{{ number_format($sold, 0, ',', '.') }}</strong> tiket terjual
                        </span>
                        <div class="eod-mini-track">
                            <div class="eod-mini-fill" data-width="{{ $relBarWidth }}"></div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Aksi Kanan -->
                <div class="eod-event-actions">
                    <a href="{{ route('eo.reports.sales', $event->id) }}" class="eod-btn-report">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                        <span>Lihat Laporan</span>
                    </a>

                    <a href="{{ route('eo.events.show', $event->id) }}" class="eod-btn-detail">
                        <span>Lihat Detail</span>
                        <span class="eod-arrow">→</span>
                    </a>
                </div>
            </div>
            @empty
            <div class="eod-empty-state">
                <div class="eod-empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                        <path d="M13 5v2"></path>
                        <path d="M13 17v2"></path>
                        <path d="M13 11v2"></path>
                    </svg>
                </div>
                <h3>Belum ada event yang dibuat</h3>
                <p>Mulai buat halaman konser, festival, atau pertunjukan pertamamu dan jual tiketnya sekarang.</p>
                <a href="{{ route('eo.events.create') }}" class="eod-btn-primary">
                    + Buat Event Pertama
                </a>
            </div>
            @endforelse

            <!-- State Kosong Hasil Filter/Pencarian -->
            <div class="eod-empty-state eod-filter-empty" id="eodFilterEmpty" style="display: none;">
                <h3>Tidak ada event yang cocok</h3>
                <p>Coba ubah kata kunci pencarian atau pilih filter status lainnya.</p>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('eodEventSearch');
        const filterButtons = document.querySelectorAll('.eod-filter-pill');
        const eventCards = document.querySelectorAll('.eod-event-card');
        const filterEmpty = document.getElementById('eodFilterEmpty');

        if (!eventCards.length) return;

        let activeFilter = 'all';

        function applyEventFilters() {
            const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
            let visibleCount = 0;

            eventCards.forEach(function(card) {
                const status = card.getAttribute('data-status') || '';
                const text = card.getAttribute('data-search') || '';
                const matchesStatus = activeFilter === 'all' || status === activeFilter;
                const matchesQuery = !query || text.includes(query);

                if (matchesStatus && matchesQuery) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (filterEmpty) {
                filterEmpty.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        filterButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                filterButtons.forEach(function(b) {
                    b.classList.remove('is-active');
                });
                btn.classList.add('is-active');
                activeFilter = btn.getAttribute('data-filter') || 'all';
                applyEventFilters();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', applyEventFilters);
        }
    });
</script>
@endsection
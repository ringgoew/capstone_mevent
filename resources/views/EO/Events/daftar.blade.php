@extends('layouts.eo')

@section('title', 'Event Saya - M!VENT')

@section('content')
<div class="eoe-page">

    <!-- Header Section -->
    <div class="eoe-header">
        <div class="eoe-header-text">
            <span class="eoe-eyebrow">M!VENT ORGANIZER</span>
            <h1>Event Saya</h1>
            <p>Kelola draf event, tiket, kuota, dan status verifikasi.</p>
        </div>

        <a href="{{ route('eo.events.create') }}"
            class="eoe-btn eoe-btn-yellow">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Buat Event</span>
        </a>
    </div>

    <!-- Flash Alerts -->
    @if (session('success'))
    <div class="eoe-alert eoe-alert-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="eoe-alert eoe-alert-error">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filter & Search Toolbar -->
    @if (count($events) > 0)
    @php
    $countAll = count($events);
    $countPublished = 0;
    $countPending = 0;
    $countDraft = 0;
    $countRejected = 0;
    foreach ($events as $item) {
    $stItem = strtolower($item->status ?? 'draft');
    if ($stItem === 'published') $countPublished++;
    elseif (in_array($stItem, ['pending', 'pending_review'])) $countPending++;
    elseif ($stItem === 'rejected') $countRejected++;
    else $countDraft++;
    }
    @endphp

    <div class="eoe-toolbar">
        <div class="eoe-filter-group" role="tablist" aria-label="Filter status event">
            <button type="button" class="eoe-filter-pill is-active" data-filter="all">
                Semua <span class="eoe-filter-count">{{ $countAll }}</span>
            </button>
            <button type="button" class="eoe-filter-pill" data-filter="published">
                Published <span class="eoe-filter-count">{{ $countPublished }}</span>
            </button>
            <button type="button" class="eoe-filter-pill" data-filter="pending">
                Pending <span class="eoe-filter-count">{{ $countPending }}</span>
            </button>
            <button type="button" class="eoe-filter-pill" data-filter="draft">
                Draft <span class="eoe-filter-count">{{ $countDraft }}</span>
            </button>
            <button type="button" class="eoe-filter-pill" data-filter="rejected">
                Rejected <span class="eoe-filter-count">{{ $countRejected }}</span>
            </button>
        </div>

        <div class="eoe-search-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="search" id="eoeSearchInput" class="eoe-search-input" placeholder="Cari event berdasarkan judul atau lokasi...">
        </div>
    </div>
    @endif

    <!-- Event List -->
    <div class="eoe-list" id="eoeEventsList">
        @forelse ($events as $event)
        @php
        $rawStatus = strtolower($event->status ?? 'draft');
        $normStatus = match ($rawStatus) {
        'published' => 'published',
        'pending', 'pending_review' => 'pending',
        'rejected' => 'rejected',
        default => 'draft',
        };
        $bannerPath = data_get($event, 'banner_image');
        @endphp

        <article class="eoe-card"
            data-status="{{ $normStatus }}"
            data-search="{{ strtolower(($event->title ?? '') . ' ' . ($event->venue_name ?? '') . ' ' . ($event->city ?? '')) }}">

            <!-- Card Top: Thumbnail/Monogram + Title + Status Badge -->
            <div class="eoe-card-top">
                <div class="eoe-title-group">
                    @if ($bannerPath)
                    <div class="eoe-thumb">
                        <img src="{{ asset('storage/' . $bannerPath) }}" alt="{{ $event->title }}" loading="lazy">
                    </div>
                    @else
                    <div class="eoe-monogram" aria-hidden="true">
                        {{ strtoupper(mb_substr($event->title ?? 'E', 0, 1)) }}
                    </div>
                    @endif

                    <div class="eoe-title-meta">
                        <h2>
                            <a href="{{ route('eo.events.show', $event->id) }}">{{ $event->title }}</a>
                        </h2>
                        <span class="eoe-slug">/{{ $event->slug }}</span>
                    </div>
                </div>

                <span class="eoe-status-badge is-{{ $normStatus }}">
                    <span class="eoe-status-dot" aria-hidden="true"></span>
                    {{ ucfirst($event->status) }}
                </span>
            </div>

            <!-- Card Middle: 3-Column Metadata Strip -->
            <div class="eoe-meta-strip">
                <div class="eoe-meta-cell">
                    <span class="eoe-meta-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </span>
                    <div>
                        <span class="eoe-meta-label">Lokasi</span>
                        <strong>
                            {{ $event->venue_name }}@if ($event->city), {{ $event->city }}@endif
                        </strong>
                    </div>
                </div>

                <div class="eoe-meta-cell">
                    <span class="eoe-meta-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </span>
                    <div>
                        <span class="eoe-meta-label">Mulai</span>
                        <strong>
                            {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y, H:i') }} WIB
                        </strong>
                    </div>
                </div>

                <div class="eoe-meta-cell">
                    <span class="eoe-meta-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </span>
                    <div>
                        <span class="eoe-meta-label">Selesai</span>
                        <strong>
                            {{ \Carbon\Carbon::parse($event->end_time)->format('d M Y, H:i') }} WIB
                        </strong>
                    </div>
                </div>
            </div>

            <!-- Contextual Status Notice / Rejection Reason -->
            @if ($event->status === 'rejected' && !empty($event->rejection_reason))
            <div class="eoe-rejection-box">
                <div class="eoe-rejection-head">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <strong>Alasan Penolakan</strong>
                </div>
                <p>{{ $event->rejection_reason }}</p>
            </div>
            @elseif ($normStatus === 'pending')
            <div class="eoe-pending-note">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span>Event sedang dalam peninjauan admin M!VENT sebelum dipublikasikan ke katalog.</span>
            </div>
            @endif

            <!-- Ticket Perforation Divider -->
            <div class="eoe-perforation" aria-hidden="true"></div>

            <!-- Card Footer Actions -->
            <div class="eoe-card-actions">
                <div class="eoe-actions-left">
                    @if (in_array($event->status, ['draft', 'rejected']))
                    <a href="{{ route('eo.events.edit', $event->id) }}"
                        class="eoe-btn eoe-btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Edit Event</span>
                    </a>
                    @endif
                </div>

                <div class="eoe-actions-right">
                    @if ($event->status === 'draft')
                    <form action="{{ route('eo.events.submit', $event->id) }}"
                        method="POST"
                        class="eoe-submit-form"
                        onsubmit="return confirm('Ajukan event ini untuk diverifikasi admin?')">
                        @csrf
                        <button type="submit"
                            class="eoe-btn eoe-btn-yellow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" aria-hidden="true">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                            <span>Ajukan Verifikasi</span>
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('eo.events.show', $event->id) }}"
                        class="eoe-btn eoe-btn-navy">
                        <span>Lihat Detail</span>
                        <span class="eoe-arrow" aria-hidden="true">→</span>
                    </a>
                </div>
            </div>

        </article>
        @empty
        <div class="eoe-empty">
            <div class="eoe-empty-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
            </div>
            <h2>Belum Ada Event</h2>
            <p>Event yang kamu buat akan muncul di halaman ini. Mulai buat event pertamamu dan jangkau ribuan penonton.</p>

            <a href="{{ route('eo.events.create') }}"
                class="eoe-btn eoe-btn-yellow">
                <span>+ Buat Event Pertama</span>
            </a>
        </div>
        @endforelse

        @if (count($events) > 0)
        <div class="eoe-empty eoe-empty-filtered" id="eoeNoMatchBox" style="display: none;">
            <h2>Event Tidak Ditemukan</h2>
            <p>Tidak ada event yang sesuai dengan filter status atau kata kunci pencarianmu.</p>
        </div>
        @endif
    </div>

</div>

@if (count($events) > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.eoe-filter-pill');
        const searchInput = document.getElementById('eoeSearchInput');
        const eventCards = document.querySelectorAll('.eoe-card');
        const noMatchBox = document.getElementById('eoeNoMatchBox');
        let currentFilter = 'all';

        function filterEvents() {
            const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
            let shown = 0;

            eventCards.forEach(function(card) {
                const matchesStatus = currentFilter === 'all' || card.dataset.status === currentFilter;
                const matchesQuery = !query || (card.dataset.search || '').includes(query);

                if (matchesStatus && matchesQuery) {
                    card.style.display = '';
                    shown++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (noMatchBox) {
                noMatchBox.style.display = shown === 0 ? '' : 'none';
            }
        }

        filterButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                filterButtons.forEach(function(item) {
                    item.classList.remove('is-active');
                });
                btn.classList.add('is-active');
                currentFilter = btn.dataset.filter || 'all';
                filterEvents();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', filterEvents);
        }
    });
</script>
@endif
@endsection
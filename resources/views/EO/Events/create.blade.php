@extends('layouts.eo')

@section('title', 'Buat Event Baru — M!VENT')

@section('content')
@php
$oldStart = old('start_time');
$oldEnd = old('end_time');

$initStartDate = $oldStart ? date('Y-m-d', strtotime($oldStart)) : '';
$initStartClock = $oldStart ? date('H:i', strtotime($oldStart)) : '';
$initEndDate = $oldEnd ? date('Y-m-d', strtotime($oldEnd)) : '';
$initEndClock = $oldEnd ? date('H:i', strtotime($oldEnd)) : '';
$isMultiDay = ($initStartDate && $initEndDate && $initStartDate !== $initEndDate);

$savedTickets = old('tickets', [
['name' => '', 'price' => '', 'quota' => '', 'max_per_order' => 5]
]);
@endphp

<div class="ce-wrapper">

    <!-- TOP HEADER (Dynamic between Step 1 and Steps 2-4 like Figma) -->
    <div class="ce-header">
        <a href="{{ route('eo.events.index') }}" class="ce-back-link" title="Kembali ke Daftar Event">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
        </a>
        <div class="ce-header-text">
            <h1 id="ce-dynamic-title">Buat Event Baru</h1>
            <div class="ce-header-meta" id="ce-dynamic-meta" style="display: none;">
                <span class="ce-header-meta-line" id="ce-meta-location">Lokasi Event</span>
                <span class="ce-header-meta-line" id="ce-meta-time">Waktu Event</span>
            </div>
        </div>
    </div>

    <!-- 4-STEP PROGRESS BAR (Figma: Edit — Banner — Ticketing — Review) -->
    <div class="ce-stepper" role="tablist" aria-label="Tahapan Pembuatan Event">
        <button type="button" class="ce-step is-active" data-step-target="1">
            <div class="ce-step-track">
                <span class="ce-step-line"></span>
                <span class="ce-step-dot"></span>
                <span class="ce-step-line"></span>
            </div>
            <span class="ce-step-label">1. Detail</span>
        </button>

        <button type="button" class="ce-step" data-step-target="2">
            <div class="ce-step-track">
                <span class="ce-step-line"></span>
                <span class="ce-step-dot"></span>
                <span class="ce-step-line"></span>
            </div>
            <span class="ce-step-label">2. Banner</span>
        </button>

        <button type="button" class="ce-step" data-step-target="3">
            <div class="ce-step-track">
                <span class="ce-step-line"></span>
                <span class="ce-step-dot"></span>
                <span class="ce-step-line"></span>
            </div>
            <span class="ce-step-label">3. Tiket</span>
        </button>

        <button type="button" class="ce-step" data-step-target="4">
            <div class="ce-step-track">
                <span class="ce-step-line"></span>
                <span class="ce-step-dot"></span>
                <span class="ce-step-line"></span>
            </div>
            <span class="ce-step-label">4. Tinjau</span>
        </button>
    </div>

    <!-- CLIENT & SERVER ALERTS -->
    <div id="ce-client-alert" class="alert alert-error" style="display: none;"></div>

    @if ($errors->any())
    <div class="alert alert-error" id="ce-server-errors">
        <strong>Periksa kembali data berikut:</strong>
        <ul style="margin-top: 6px; padding-left: 18px; list-style: disc;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form id="create-event-form" action="{{ route('eo.events.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        <!-- Hidden combined datetime inputs expected by EventController::store() -->
        <input type="hidden" name="start_time" id="start_time" value="{{ $oldStart }}">
        <input type="hidden" name="end_time" id="end_time" value="{{ $oldEnd }}">

        <!-- =================================================================
             STEP 1: EDIT / EVENT DETAILS, DATE & TIME, LOCATION, DESCRIPTION
             ================================================================= -->
        <div class="ce-step-panel is-visible" data-step-panel="1">

            <!-- Event Details -->
            <div class="ce-section-block">
                <h2 class="ce-section-heading">Detail Event</h2>

                <div class="ce-field-row">
                    <label for="title" class="ce-field-label">Nama Event<span class="ce-required">*</span></label>
                    <div>
                        <input type="text" id="title" name="title" class="ce-input" value="{{ old('title') }}" placeholder="Masukkan nama konser atau festival musik" required>
                    </div>
                </div>

                <div class="ce-field-row">
                    <label for="category_id" class="ce-field-label">Kategori Event<span class="ce-required">*</span></label>
                    <div>
                        <select id="category_id" name="category_id" class="ce-select" required>
                            <option value="">Pilih kategori event</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Date & Time -->
            <div class="ce-section-block">
                <h2 class="ce-section-heading">Tanggal & Waktu</h2>

                <div class="ce-field-row">
                    <span class="ce-field-label">Jenis Jadwal<span class="ce-required">*</span></span>
                    <div class="ce-radio-group">
                        <label class="ce-radio-option">
                            <input type="radio" name="schedule_mode" value="single" {{ !$isMultiDay ? 'checked' : '' }}>
                            <span>Event Satu Hari</span>
                        </label>
                        <label class="ce-radio-option">
                            <input type="radio" name="schedule_mode" value="multiday" {{ $isMultiDay ? 'checked' : '' }}>
                            <span>Event Multi-Hari</span>
                        </label>
                    </div>
                </div>

                <div class="ce-field-row">
                    <span class="ce-field-label">Sesi Waktu<span class="ce-required">*</span></span>
                    <div class="ce-datetime-grid {{ $isMultiDay ? 'has-end-date' : '' }}" id="ce-datetime-grid">
                        <div>
                            <label for="start_date_input" class="ce-sub-label">Tanggal Mulai<span class="ce-required">*</span></label>
                            <div class="ce-icon-input-wrap">
                                <span class="ce-input-prefix">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                </span>
                                <input type="date" id="start_date_input" value="{{ $initStartDate }}" required>
                            </div>
                        </div>

                        <div id="end_date_col" @if(!$isMultiDay) style="display: none;" @endif>
                            <label for="end_date_input" class="ce-sub-label">Tanggal Selesai<span class="ce-required">*</span></label>
                            <div class="ce-icon-input-wrap">
                                <span class="ce-input-prefix">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                </span>
                                <input type="date" id="end_date_input" value="{{ $initEndDate }}">
                            </div>
                        </div>

                        <div>
                            <label for="start_clock_input" class="ce-sub-label">Waktu Mulai<span class="ce-required">*</span></label>
                            <div class="ce-icon-input-wrap">
                                <span class="ce-input-prefix">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                </span>
                                <input type="time" id="start_clock_input" value="{{ $initStartClock }}" required>
                            </div>
                        </div>

                        <div>
                            <label for="end_clock_input" class="ce-sub-label">Waktu Selesai<span class="ce-required">*</span></label>
                            <div class="ce-icon-input-wrap">
                                <span class="ce-input-prefix">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                </span>
                                <input type="time" id="end_clock_input" value="{{ $initEndClock }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="ce-section-block">
                <h2 class="ce-section-heading">Lokasi Event</h2>

                <div class="ce-field-row">
                    <label for="venue_name" class="ce-field-label">Nama Venue<span class="ce-required">*</span></label>
                    <div>
                        <input type="text" id="venue_name" name="venue_name" class="ce-input" value="{{ old('venue_name') }}" placeholder="Contoh: Stadion Utama Gelora Bung Karno" required>
                    </div>
                </div>

                <div class="ce-field-row">
                    <label for="city" class="ce-field-label">Kota<span class="ce-required">*</span></label>
                    <div>
                        <input type="text" id="city" name="city" class="ce-input" value="{{ old('city') }}" placeholder="Contoh: Jakarta Pusat" required>
                    </div>
                </div>

                <div class="ce-field-row">
                    <label for="address" class="ce-field-label">Alamat Lengkap<span class="ce-required">*</span></label>
                    <div>
                        <input type="text" id="address" name="address" class="ce-input" value="{{ old('address') }}" placeholder="Tuliskan alamat lengkap venue beserta patokan lokasi" required>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="ce-section-block">
                <h2 class="ce-section-heading">Informasi Tambahan</h2>

                <div class="ce-field-row">
                    <label for="description" class="ce-field-label">Deskripsi Event<span class="ce-required">*</span></label>
                    <div>
                        <textarea id="description" name="description" class="ce-textarea" placeholder="Jelaskan detail acara, daftar penampil (line-up), peraturan masuk, dan informasi penting lainnya..." required>{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="ce-actions">
                <button type="button" class="ce-btn-navy" onclick="goToStep(2)">Simpan & Lanjutkan</button>
            </div>
        </div>

        <!-- =================================================================
             STEP 2: BANNER UPLOAD
             ================================================================= -->
        <div class="ce-step-panel" data-step-panel="2">
            <div class="ce-section-block">
                <h2 class="ce-section-heading-flush">Unggah Banner Event</h2>

                <input type="file" id="banner_image" name="banner_image" accept=".jpg,.jpeg,.png" style="position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none;" required>

                <div class="ce-file-picker" id="ce-file-trigger" role="button" tabindex="0">
                    <span class="ce-file-btn-badge">Pilih File</span>
                    <span class="ce-file-name-text" id="ce-file-name-display">Belum ada file dipilih</span>
                </div>

                <div class="ce-file-hints">
                    <span>Rekomendasi dimensi banner: minimal lebar 1170 px × tinggi 504 px (rasio lanskap).</span>
                    <span>Format file yang didukung: JPG, JPEG, atau PNG (Ukuran maksimal 2 MB).</span>
                </div>

                <div class="ce-banner-preview-box" id="ce-step2-preview-box">
                    <img id="ce-step2-preview-img" src="" alt="Pratinjau Banner Event">
                </div>
            </div>

            <div class="ce-actions">
                <button type="button" class="ce-btn-ghost" onclick="goToStep(1)">Kembali ke Detail</button>
                <button type="button" class="ce-btn-navy" onclick="goToStep(3)">Simpan & Lanjutkan</button>
            </div>
        </div>

        <!-- =================================================================
             STEP 3: TICKETING
             ================================================================= -->
        <div class="ce-step-panel" data-step-panel="3">
            <div class="ce-section-block">
                <h2 class="ce-section-heading-flush">Jenis event apa yang Anda selenggarakan?</h2>

                <div class="ce-ticket-type-grid">
                    <div class="ce-type-card is-selected" id="card-ticketed" onclick="setEventPricingMode('ticketed')">
                        <div class="ce-type-card-icon">
                            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                                <path d="M13 5v2" />
                                <path d="M13 17v2" />
                                <path d="M13 11v2" />
                            </svg>
                        </div>
                        <div class="ce-type-card-title">Event Bertiket (Berbayar)</div>
                        <div class="ce-type-card-desc">Pengunjung membeli tiket berbayar untuk masuk ke acara</div>
                    </div>

                    <div class="ce-type-card" id="card-free" onclick="setEventPricingMode('free')">
                        <div class="ce-type-card-icon">
                            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M8 12h8" />
                                <path d="M12 8v8" />
                            </svg>
                        </div>
                        <div class="ce-type-card-title">Event Gratis</div>
                        <div class="ce-type-card-desc">Pengunjung dapat mengklaim tiket gratis (Rp 0)</div>
                    </div>
                </div>

                <h2 class="ce-section-heading-flush">Tiket apa saja yang Anda sediakan?</h2>

                <div class="ce-ticket-rows" id="ticket-container" data-initial-count="{{ count($savedTickets) }}">
                    @foreach ($savedTickets as $idx => $ticket)
                    <div class="ce-ticket-row">
                        <div>
                            <label class="ce-sub-label">Nama Tiket<span class="ce-required">*</span></label>
                            <input type="text" name="tickets[{{ $idx }}][name]" class="ce-input ticket-name-input" value="{{ $ticket['name'] ?? '' }}" placeholder="Contoh: General Admission / VIP" required>
                        </div>

                        <div>
                            <label class="ce-sub-label">Harga Tiket<span class="ce-required">*</span></label>
                            <div class="ce-icon-input-wrap">
                                <span class="ce-input-prefix">Rp</span>
                                <input type="number" name="tickets[{{ $idx }}][price]" class="ticket-price-input" value="{{ $ticket['price'] ?? '' }}" placeholder="0" min="0" required>
                            </div>
                        </div>

                        <div>
                            <label class="ce-sub-label">Kuota<span class="ce-required">*</span></label>
                            <input type="number" name="tickets[{{ $idx }}][quota]" class="ce-input ticket-quota-input" value="{{ $ticket['quota'] ?? '' }}" placeholder="100" min="1" required>
                        </div>

                        <div>
                            <label class="ce-sub-label">Maks / Order<span class="ce-required">*</span></label>
                            <input type="number" name="tickets[{{ $idx }}][max_per_order]" class="ce-input ticket-max-input" value="{{ $ticket['max_per_order'] ?? 5 }}" min="1" required>
                        </div>

                        <div class="ce-ticket-circle-actions">
                            <button type="button" class="ce-circle-btn is-remove" onclick="removeTicketRow(this)" title="Hapus Jenis Tiket">−</button>
                            <button type="button" class="ce-circle-btn" onclick="addTicketRow()" title="Tambah Jenis Tiket">+</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="ce-actions">
                <button type="button" class="ce-btn-ghost" onclick="goToStep(2)">Kembali ke Banner</button>
                <button type="button" class="ce-btn-navy" onclick="goToStep(4)">Simpan & Lanjutkan</button>
            </div>
        </div>

        <!-- =================================================================
             STEP 4: REVIEW & SUBMIT FOR ADMIN VERIFICATION
             ================================================================= -->
        <div class="ce-step-panel" data-step-panel="4">
            <p class="ce-review-intro">Hampir selesai! Periksa kembali seluruh detail event Anda sebelum diajukan ke Admin.</p>

            <div class="ce-review-card">
                <div class="ce-review-banner">
                    <img id="rev-banner-img" src="" alt="Banner Event" style="display: none;">
                    <div class="ce-review-banner-placeholder" id="rev-banner-placeholder">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                        <span>Pratinjau Banner Event</span>
                    </div>
                </div>

                <div class="ce-review-badge-row">
                    <span class="category-badge" id="rev-category">Kategori Event</span>
                    <span class="status-pending event-status">Status Awal: Menunggu Verifikasi Admin</span>
                </div>

                <h2 class="ce-review-title" id="rev-title">Judul Event</h2>

                <!-- Date & Time + Ticket Information -->
                <div class="ce-review-two-col">
                    <div>
                        <h3 class="ce-review-subhead">Tanggal & Waktu</h3>
                        <div class="ce-review-info-line">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            <span id="rev-date-text">-</span>
                        </div>
                        <div class="ce-review-info-line">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            <span id="rev-time-text">-</span>
                        </div>
                    </div>

                    <div>
                        <h3 class="ce-review-subhead">Informasi Tiket</h3>
                        <div id="rev-tickets-list"></div>
                    </div>
                </div>

                <!-- Location -->
                <h3 class="ce-review-subhead">Lokasi</h3>
                <div class="ce-review-info-line">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    <span id="rev-location-text">-</span>
                </div>
                <div class="ce-review-map-box">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="#EF4444" stroke="#991B1B" stroke-width="1.5" style="margin-bottom: 6px;">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" fill="#FFFFFF" />
                    </svg>
                    <strong id="rev-map-venue" style="font-size: 14px; color: #0F172A;">Venue Event</strong>
                    <span id="rev-map-address" style="font-size: 12px; color: #475569; margin-top: 2px;">Alamat lengkap akan ditampilkan kepada pembeli tiket</span>
                </div>

                <!-- Hosted By -->
                <h3 class="ce-review-subhead">Diselenggarakan Oleh</h3>
                <div class="ce-review-host-box">
                    <div class="ce-review-host-avatar">
                        {{ strtoupper(substr(session('eo_name', 'EO'), 0, 2)) }}
                    </div>
                    <div>
                        <div style="font-size: 17px; font-weight: 700; color: #2D2C3C;">{{ session('eo_name', 'Event Organizer') }}</div>
                        <div style="font-size: 13px; color: #64748B;">Terverifikasi di M!VENT</div>
                    </div>
                </div>

                <!-- Event Description -->
                <h3 class="ce-review-subhead">Deskripsi Event</h3>
                <p class="ce-review-desc-text" id="rev-description">-</p>
            </div>

            <div class="ce-admin-notice">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 2px;">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="16" x2="12" y2="12" />
                    <line x1="12" y1="8" x2="12.01" y2="8" />
                </svg>
                <div>
                    <strong>Ketentuan Publikasi M!VENT:</strong> Event yang Anda kirimkan tidak langsung tayang ke publik, melainkan masuk ke antrean <strong>Verifikasi Admin</strong> (status <em>Pending</em>). Pastikan judul dan deskripsi bebas dari konten terlarang agar tidak otomatis ditahan sebagai draf.
                </div>
            </div>

            <div class="ce-actions">
                <button type="button" class="ce-btn-ghost" onclick="goToStep(3)">Kembali ke Tiket</button>
                <button type="button" class="ce-btn-yellow" onclick="goToStep(1)">Ubah Detail</button>
                <button type="submit" class="ce-btn-navy">Simpan & Ajukan Event</button>
            </div>
        </div>

    </form>
</div>

<script>
    let currentStep = 1;
    let pricingMode = 'ticketed';
    let bannerDataUrl = '';
    let ticketIndex = parseInt(document.getElementById('ticket-container').getAttribute('data-initial-count')) || 1;

    document.addEventListener('DOMContentLoaded', function() {
        // 1. Sync date & time inputs into hidden start_time & end_time fields
        const scheduleRadios = document.querySelectorAll('input[name="schedule_mode"]');
        const endDateCol = document.getElementById('end_date_col');
        const dtGrid = document.getElementById('ce-datetime-grid');

        scheduleRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const isMulti = this.value === 'multiday';
                endDateCol.style.display = isMulti ? '' : 'none';
                dtGrid.classList.toggle('has-end-date', isMulti);
                syncDateTimeFields();
            });
        });

        ['start_date_input', 'end_date_input', 'start_clock_input', 'end_clock_input'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', syncDateTimeFields);
        });
        syncDateTimeFields();

        // 2. Custom Banner File Input & Preview
        const fileInput = document.getElementById('banner_image');
        const fileTrigger = document.getElementById('ce-file-trigger');
        const fileNameDisplay = document.getElementById('ce-file-name-display');
        const step2PreviewBox = document.getElementById('ce-step2-preview-box');
        const step2PreviewImg = document.getElementById('ce-step2-preview-img');

        fileTrigger.addEventListener('click', () => fileInput.click());
        fileTrigger.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                fileInput.click();
            }
        });

        fileInput.addEventListener('change', function() {
            const file = this.files && this.files[0];
            if (!file) {
                fileNameDisplay.textContent = 'Belum ada file dipilih';
                step2PreviewBox.classList.remove('has-image');
                bannerDataUrl = '';
                return;
            }

            fileNameDisplay.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                bannerDataUrl = e.target.result;
                step2PreviewImg.src = bannerDataUrl;
                step2PreviewBox.classList.add('has-image');
            };
            reader.readAsDataURL(file);
        });

        // 3. Stepper click navigation
        document.querySelectorAll('.ce-step').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = parseInt(this.getAttribute('data-step-target'));
                if (target < currentStep) {
                    goToStep(target);
                } else if (target > currentStep) {
                    // Validate step-by-step forward
                    for (let s = currentStep; s < target; s++) {
                        if (!validateStep(s)) return;
                    }
                    goToStep(target);
                }
            });
        });

        // 4. Auto-detect initial Free vs Ticketed mode on validation reload
        const priceInputs = document.querySelectorAll('.ticket-price-input');
        if (priceInputs.length > 0 && Array.from(priceInputs).every(inp => inp.value !== '' && Number(inp.value) === 0)) {
            setEventPricingMode('free');
        }

        updateRemoveButtonsState();
    });

    function syncDateTimeFields() {
        const startDate = document.getElementById('start_date_input').value;
        const startClock = document.getElementById('start_clock_input').value;
        const endClock = document.getElementById('end_clock_input').value;
        const isMulti = document.querySelector('input[name="schedule_mode"]:checked').value === 'multiday';
        const endDate = isMulti ? (document.getElementById('end_date_input').value || startDate) : startDate;

        document.getElementById('start_time').value = (startDate && startClock) ? `${startDate}T${startClock}` : '';
        document.getElementById('end_time').value = (endDate && endClock) ? `${endDate}T${endClock}` : '';
    }

    function showClientAlert(message) {
        const box = document.getElementById('ce-client-alert');
        if (!message) {
            box.style.display = 'none';
            box.textContent = '';
            return;
        }
        box.textContent = message;
        box.style.display = 'block';
        box.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }

    function validateStep(step) {
        showClientAlert('');
        syncDateTimeFields();

        if (step === 1) {
            const title = document.getElementById('title').value.trim();
            const category = document.getElementById('category_id').value;
            const startTime = document.getElementById('start_time').value;
            const endTime = document.getElementById('end_time').value;
            const venue = document.getElementById('venue_name').value.trim();
            const city = document.getElementById('city').value.trim();
            const address = document.getElementById('address').value.trim();
            const desc = document.getElementById('description').value.trim();

            if (!title) return showClientAlert('Nama Event wajib diisi.'), false;
            if (!category) return showClientAlert('Kategori Event wajib dipilih.'), false;
            if (!startTime || !endTime) return showClientAlert('Tanggal dan waktu mulai serta selesai event wajib dilengkapi.'), false;
            if (new Date(endTime) <= new Date(startTime)) return showClientAlert('Waktu selesai event harus setelah waktu mulai.'), false;
            if (!venue || !city || !address) return showClientAlert('Nama venue, kota, dan alamat lengkap wajib diisi.'), false;
            if (!desc) return showClientAlert('Deskripsi Event wajib diisi.'), false;
        }

        if (step === 2) {
            const fileInput = document.getElementById('banner_image');
            if (!fileInput.files || fileInput.files.length === 0) {
                return showClientAlert('Silakan unggah banner event (JPG, JPEG, atau PNG maks. 2 MB) terlebih dahulu.'), false;
            }
            const file = fileInput.files[0];
            if (file.size > 2 * 1024 * 1024) {
                return showClientAlert('Ukuran file banner melebihi batas maksimal 2 MB.'), false;
            }
        }

        if (step === 3) {
            const rows = document.querySelectorAll('#ticket-container .ce-ticket-row');
            if (rows.length === 0) {
                return showClientAlert('Tambahkan minimal satu jenis tiket.'), false;
            }
            let totalQuota = 0;
            for (let i = 0; i < rows.length; i++) {
                const name = rows[i].querySelector('.ticket-name-input').value.trim();
                const price = rows[i].querySelector('.ticket-price-input').value;
                const quota = parseInt(rows[i].querySelector('.ticket-quota-input').value);
                const maxOrder = parseInt(rows[i].querySelector('.ticket-max-input').value);

                if (!name) return showClientAlert(`Nama tiket pada baris #${i + 1} wajib diisi.`), false;
                if (price === '' || Number(price) < 0) return showClientAlert(`Harga tiket pada baris #${i + 1} tidak valid.`), false;
                if (!quota || quota < 1) return showClientAlert(`Kuota tiket pada baris #${i + 1} minimal 1.`), false;
                if (!maxOrder || maxOrder < 1) return showClientAlert(`Maksimal tiket per transaksi pada baris #${i + 1} minimal 1.`), false;
                totalQuota += quota;
            }
            if (totalQuota > 10000) {
                return showClientAlert(`Total kuota seluruh tiket (${totalQuota}) melebihi batas maksimal 10.000.`), false;
            }
        }

        return true;
    }

    function goToStep(targetStep) {
        if (targetStep > currentStep) {
            for (let s = currentStep; s < targetStep; s++) {
                if (!validateStep(s)) return;
            }
        } else {
            showClientAlert('');
        }

        currentStep = targetStep;

        // Toggle panels
        document.querySelectorAll('.ce-step-panel').forEach(panel => {
            panel.classList.toggle('is-visible', parseInt(panel.getAttribute('data-step-panel')) === currentStep);
        });

        // Update stepper indicators
        document.querySelectorAll('.ce-step').forEach(stepBtn => {
            const sNum = parseInt(stepBtn.getAttribute('data-step-target'));
            stepBtn.classList.toggle('is-active', sNum === currentStep);
            stepBtn.classList.toggle('is-completed', sNum < currentStep);
        });

        updateTopSummaryHeader();
        if (currentStep === 4) {
            populateReviewPreview();
        }

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function updateTopSummaryHeader() {
        const titleEl = document.getElementById('ce-dynamic-title');
        const metaEl = document.getElementById('ce-dynamic-meta');

        if (currentStep === 1) {
            titleEl.textContent = 'Buat Event Baru';
            metaEl.style.display = 'none';
            return;
        }

        const titleVal = document.getElementById('title').value.trim() || 'Judul Event';
        const venueVal = document.getElementById('venue_name').value.trim();
        const cityVal = document.getElementById('city').value.trim();
        const startDateVal = document.getElementById('start_date_input').value;
        const startClockVal = document.getElementById('start_clock_input').value;
        const endClockVal = document.getElementById('end_clock_input').value;

        titleEl.textContent = titleVal;
        document.getElementById('ce-meta-location').textContent = [venueVal, cityVal].filter(Boolean).join(', ') || 'Lokasi belum diisi';
        document.getElementById('ce-meta-time').textContent = startDateVal ?
            `${formatDateIndo(startDateVal)} • ${startClockVal || '--:--'} - ${endClockVal || '--:--'} WIB` :
            'Jadwal belum diisi';
        metaEl.style.display = 'flex';
    }

    function setEventPricingMode(mode) {
        pricingMode = mode;
        document.getElementById('card-ticketed').classList.toggle('is-selected', mode === 'ticketed');
        document.getElementById('card-free').classList.toggle('is-selected', mode === 'free');

        document.querySelectorAll('.ticket-price-input').forEach(input => {
            if (mode === 'free') {
                input.value = 0;
                input.readOnly = true;
                input.style.backgroundColor = '#F1F5F9';
            } else {
                input.readOnly = false;
                input.style.backgroundColor = 'transparent';
                if (input.value === '0') input.value = '';
            }
        });
    }

    function addTicketRow() {
        const container = document.getElementById('ticket-container');
        const isFree = pricingMode === 'free';
        const row = document.createElement('div');
        row.className = 'ce-ticket-row';

        row.innerHTML = `
            <div>
                <label class="ce-sub-label">Nama Tiket<span class="ce-required">*</span></label>
                <input type="text" name="tickets[${ticketIndex}][name]" class="ce-input ticket-name-input" placeholder="Contoh: Reguler / VIP" required>
            </div>
            <div>
                <label class="ce-sub-label">Harga Tiket<span class="ce-required">*</span></label>
                <div class="ce-icon-input-wrap">
                    <span class="ce-input-prefix">Rp</span>
                    <input type="number" name="tickets[${ticketIndex}][price]" class="ticket-price-input" value="${isFree ? '0' : ''}" placeholder="0" min="0" ${isFree ? 'readonly style="background-color:#F1F5F9;"' : ''} required>
                </div>
            </div>
            <div>
                <label class="ce-sub-label">Kuota<span class="ce-required">*</span></label>
                <input type="number" name="tickets[${ticketIndex}][quota]" class="ce-input ticket-quota-input" placeholder="100" min="1" required>
            </div>
            <div>
                <label class="ce-sub-label">Maks / Order<span class="ce-required">*</span></label>
                <input type="number" name="tickets[${ticketIndex}][max_per_order]" class="ce-input ticket-max-input" value="5" min="1" required>
            </div>
            <div class="ce-ticket-circle-actions">
                <button type="button" class="ce-circle-btn is-remove" onclick="removeTicketRow(this)" title="Hapus Jenis Tiket">−</button>
                <button type="button" class="ce-circle-btn" onclick="addTicketRow()" title="Tambah Jenis Tiket">+</button>
            </div>
        `;

        container.appendChild(row);
        ticketIndex++;
        updateRemoveButtonsState();
    }

    function removeTicketRow(button) {
        const rows = document.querySelectorAll('#ticket-container .ce-ticket-row');
        if (rows.length > 1) {
            button.closest('.ce-ticket-row').remove();
            updateRemoveButtonsState();
        }
    }

    function updateRemoveButtonsState() {
        const rows = document.querySelectorAll('#ticket-container .ce-ticket-row');
        rows.forEach(row => {
            const rmBtn = row.querySelector('.is-remove');
            if (rmBtn) rmBtn.disabled = rows.length === 1;
        });
    }

    function populateReviewPreview() {
        // Banner
        const revImg = document.getElementById('rev-banner-img');
        const revPlaceholder = document.getElementById('rev-banner-placeholder');
        if (bannerDataUrl) {
            revImg.src = bannerDataUrl;
            revImg.style.display = 'block';
            revPlaceholder.style.display = 'none';
        } else {
            revImg.style.display = 'none';
            revPlaceholder.style.display = 'flex';
        }

        // Category & Title
        const catSelect = document.getElementById('category_id');
        const catText = catSelect.options[catSelect.selectedIndex]?.text || 'Kategori Event';
        document.getElementById('rev-category').textContent = catText;
        document.getElementById('rev-title').textContent = document.getElementById('title').value.trim();

        // Date & Time
        const startDate = document.getElementById('start_date_input').value;
        const isMulti = document.querySelector('input[name="schedule_mode"]:checked').value === 'multiday';
        const endDate = isMulti ? (document.getElementById('end_date_input').value || startDate) : startDate;
        const startClock = document.getElementById('start_clock_input').value;
        const endClock = document.getElementById('end_clock_input').value;

        document.getElementById('rev-date-text').textContent = (isMulti && endDate !== startDate) ?
            `${formatDateIndo(startDate)} – ${formatDateIndo(endDate)}` :
            formatDateIndo(startDate);
        document.getElementById('rev-time-text').textContent = `${startClock} – ${endClock} WIB`;

        // Tickets
        const revTicketsList = document.getElementById('rev-tickets-list');
        revTicketsList.innerHTML = '';
        document.querySelectorAll('#ticket-container .ce-ticket-row').forEach(row => {
            const name = row.querySelector('.ticket-name-input').value.trim();
            const price = Number(row.querySelector('.ticket-price-input').value || 0);
            const quota = row.querySelector('.ticket-quota-input').value;
            const priceFormatted = price === 0 ?
                'Gratis (Rp 0)' :
                'Rp ' + price.toLocaleString('id-ID');

            const item = document.createElement('div');
            item.className = 'ce-review-info-line';
            item.innerHTML = `
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/>
                </svg>
                <span><strong>${escapeHtml(name)}</strong>: ${priceFormatted} / tiket (Kuota: ${escapeHtml(quota)})</span>
            `;
            revTicketsList.appendChild(item);
        });

        // Location & Description
        const venue = document.getElementById('venue_name').value.trim();
        const city = document.getElementById('city').value.trim();
        const address = document.getElementById('address').value.trim();

        document.getElementById('rev-location-text').textContent = `${venue}, ${city} — ${address}`;
        document.getElementById('rev-map-venue').textContent = `${venue} (${city})`;
        document.getElementById('rev-map-address').textContent = address;
        document.getElementById('rev-description').textContent = document.getElementById('description').value.trim();
    }

    function formatDateIndo(isoDate) {
        if (!isoDate) return '-';
        const [y, m, d] = isoDate.split('-').map(Number);
        const dateObj = new Date(y, m - 1, d);
        return dateObj.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
</script>
@endsection
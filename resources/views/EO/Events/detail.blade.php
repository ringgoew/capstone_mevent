@extends('layouts.eo')

@section('title', 'Detail Event — ' . $event->title)

@section('content')
    @php
        $eoId = session('eo_user_id');

        // Mengambil daftar panitia khusus milik EO yang sedang login
        $daftarPanitia =
            $panitia ??
            (class_exists(\App\Models\Panitia::class) ? \App\Models\Panitia::getDaftarPanitia($eoId) : collect());

        // Resolusi nama route panitia secara defensif
        $routePanitiaStore = Route::has('panitia.store')
            ? route('panitia.store')
            : (Route::has('eo.panitia.store')
                ? route('eo.panitia.store')
                : '#');

        $routePanitiaDestroyName = Route::has('panitia.destroy')
            ? 'panitia.destroy'
            : (Route::has('eo.panitia.destroy')
                ? 'eo.panitia.destroy'
                : null);
    @endphp

    <div class="eoe-detail-wrapper">
        <div class="eoe-detail-container">

            <!-- Flash Alert Feedback -->
            @if (session('success'))
                <div class="eoe-alert eoe-alert-success" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor"
                        stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="eoe-alert eoe-alert-error" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                        stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="eoe-alert eoe-alert-error" role="alert">
                    <div>
                        <strong>Gagal menambahkan panitia:</strong>
                        <ul class="eoe-alert-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Top Navigation Bar -->
            <div class="eoe-detail-topbar">
                <a href="{{ route('eo.events.index') }}" class="eoe-btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                        stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" aria-hidden="true">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Daftar Event</span>
                </a>

                <div class="eoe-topbar-actions">
                    <!-- Tombol Cepat Add Panitia -->
                    <button type="button" class="eoe-btn eoe-btn-navy" onclick="openAddPanitiaModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                            stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                        <span>Add Panitia</span>
                    </button>

                    @if (in_array(strtolower($event->status ?? 'draft'), ['draft', 'rejected']))
                        <a href="{{ route('eo.events.edit', $event->id) }}" class="eoe-btn eoe-btn-yellow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                                stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            <span>Edit Event</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Card Utama Event -->
            <div class="eoe-hero-card">
                @if ($event->banner_image)
                    <div class="eoe-banner-wrap">
                        <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}"
                            class="eoe-banner-img" loading="lazy">
                    </div>
                @else
                    <div class="eoe-banner-fallback" aria-hidden="true">
                        <span class="eoe-banner-fallback-badge">M!VENT SHOWCASE</span>
                        <span class="eoe-banner-fallback-title">{{ $event->title }}</span>
                    </div>
                @endif

                <div class="eoe-hero-body">
                    @php
                        $status = strtolower($event->status ?? 'draft');
                        $statusClass = match ($status) {
                            'published' => 'is-published',
                            'pending', 'pending_review' => 'is-pending',
                            'rejected', 'cancelled' => 'is-rejected',
                            default => 'is-draft',
                        };
                    @endphp

                    <div class="eoe-hero-badges">
                        @if ($category)
                            <span class="eoe-category-badge">{{ $category->name }}</span>
                        @endif

                        <span class="eoe-status-badge {{ $statusClass }}">
                            <span class="eoe-status-dot" aria-hidden="true"></span>
                            Status: {{ ucfirst($status) }}
                        </span>
                    </div>

                    <h1 class="eoe-hero-title">{{ $event->title }}</h1>
                    <p class="eoe-hero-desc">{{ $event->description }}</p>

                    <!-- 3-Card Bento Metadata Grid -->
                    <div class="eoe-detail-meta-grid">
                        <div class="eoe-detail-meta-item">
                            <div class="eoe-detail-meta-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                    stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                            <div>
                                <span class="eoe-detail-meta-label">Waktu Mulai</span>
                                <strong
                                    class="eoe-detail-meta-value">{{ \Carbon\Carbon::parse($event->start_time)->format('d M Y, H:i') }}
                                    WIB</strong>
                            </div>
                        </div>

                        <div class="eoe-detail-meta-item">
                            <div class="eoe-detail-meta-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                    stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <div>
                                <span class="eoe-detail-meta-label">Waktu Selesai</span>
                                <strong
                                    class="eoe-detail-meta-value">{{ \Carbon\Carbon::parse($event->end_time)->format('d M Y, H:i') }}
                                    WIB</strong>
                            </div>
                        </div>

                        <div class="eoe-detail-meta-item eoe-meta-span-full">
                            <div class="eoe-detail-meta-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                    stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <div>
                                <span class="eoe-detail-meta-label">Lokasi Tempat</span>
                                <strong class="eoe-detail-meta-value">{{ $event->venue_name }},
                                    {{ $event->city }}</strong>
                                @if ($event->address)
                                    <span class="eoe-detail-meta-sub">{{ $event->address }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Perforation & Organizer Strip -->
                    <div class="eoe-perforation" aria-hidden="true"></div>

                    <div class="eoe-host-strip">
                        <div class="eoe-host-info">
                            <span class="eoe-host-avatar" aria-hidden="true">
                                {{ strtoupper(mb_substr(session('eo_nama', 'EO'), 0, 2)) }}
                            </span>
                            <div>
                                <span class="eoe-host-label">Diselenggarakan oleh</span>
                                <strong class="eoe-host-name">{{ session('eo_nama', 'Event Organizer') }}</strong>
                            </div>
                        </div>

                        @if (!empty($event->slug))
                            <span class="eoe-slug-pill">Slug: {{ $event->slug }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Section Tiket -->
            <div class="eoe-tickets-section">
                <div class="eoe-tickets-header">
                    <div>
                        <h2 class="eoe-section-title">Pilihan Tiket &amp; Kuota</h2>
                        <p class="eoe-section-subtitle">Rincian kategori tiket, harga satuan, dan progres kuota terjual.
                        </p>
                    </div>
                    <span class="eoe-ticket-count-pill">{{ count($tickets) }} Kategori Tiket</span>
                </div>

                <div class="eoe-tickets-grid">
                    @forelse ($tickets as $ticket)
                        @php
                            $sold = (int) ($ticket->sold_quota ?? 0);
                            $quota = (int) ($ticket->quota ?? 0);
                            $remaining = max(0, $quota - $sold);
                            $soldPct = $quota > 0 ? min(100, round(($sold / $quota) * 100)) : 0;
                        @endphp

                        <div class="eoe-ticket-stub">
                            <div class="eoe-ticket-main">
                                <div class="eoe-ticket-title-row">
                                    <h3 class="eoe-ticket-name">{{ $ticket->name }}</h3>
                                    @if ($quota > 0 && $remaining === 0)
                                        <span class="eoe-ticket-avail-tag is-soldout">Habis</span>
                                    @else
                                        <span class="eoe-ticket-avail-tag is-available">Tersedia</span>
                                    @endif
                                </div>

                                <p class="eoe-ticket-quota">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                        stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span>Kuota: <strong>{{ number_format($quota, 0, ',', '.') }}</strong> tiket</span>
                                    <span class="eoe-dot-sep" aria-hidden="true">•</span>
                                    <span>Terjual: <strong>{{ number_format($sold, 0, ',', '.') }}</strong> tiket</span>
                                    <span class="eoe-dot-sep" aria-hidden="true">•</span>
                                    <span>Sisa: <strong>{{ number_format($remaining, 0, ',', '.') }}</strong> tiket</span>
                                </p>

                                <div class="eoe-ticket-progress-wrap">
                                    <div class="eoe-ticket-progress-track">
                                        <div class="eoe-ticket-progress-fill" style="width: {{ $soldPct }}%;"></div>
                                    </div>
                                    <span class="eoe-ticket-progress-pct">{{ $soldPct }}%</span>
                                </div>
                            </div>

                            <div class="eoe-ticket-stub-divider" aria-hidden="true"></div>

                            <div class="eoe-ticket-action">
                                <span class="eoe-ticket-price-label">HARGA TIKET</span>
                                @if ($ticket->price > 0)
                                    <span class="eoe-ticket-price">
                                        Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="eoe-ticket-free-badge">GRATIS</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="eoe-empty-tickets">
                            <p>Belum ada jenis tiket yang dibuat untuk event ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Section Kelola Panitia Event -->
            <div class="eoe-panitia-section" id="kelola-panitia">
                <div class="eoe-tickets-header">
                    <div>
                        <h2 class="eoe-section-title">Tim Panitia Lapangan</h2>
                        <p class="eoe-section-subtitle">Kelola akses akun panitia untuk validasi tiket dan operasional
                            check-in pengunjung.</p>
                    </div>

                    <div class="eoe-panitia-header-actions">
                        <span class="eoe-ticket-count-pill">{{ count($daftarPanitia) }} Panitia</span>
                        <button type="button" class="eoe-btn eoe-btn-yellow" onclick="openAddPanitiaModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                                stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" aria-hidden="true">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Add Panitia</span>
                        </button>
                    </div>
                </div>

                <div class="eoe-panitia-grid">
                    @forelse ($daftarPanitia as $item)
                        <div class="eoe-panitia-card">
                            <div class="eoe-panitia-identity">
                                <div class="eoe-panitia-avatar" aria-hidden="true">
                                    {{ strtoupper(mb_substr($item->name ?? 'P', 0, 2)) }}
                                </div>
                                <div class="eoe-panitia-info">
                                    <div class="eoe-panitia-name-row">
                                        <h3 class="eoe-panitia-name">{{ $item->name }}</h3>
                                        <span class="eoe-panitia-role-tag">Panitia</span>
                                    </div>
                                    <p class="eoe-panitia-email">{{ $item->email }}</p>
                                </div>
                            </div>

                            <div class="eoe-panitia-actions">
                                @if ($routePanitiaDestroyName)
                                    <form id="delete-panitia-form-{{ $item->id }}"
                                        action="{{ route($routePanitiaDestroyName, $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="eoe-btn-delete-panitia"
                                            onclick="confirmDeletePanitia({{ $item->id }}, '{{ addslashes($item->name) }}')"
                                            title="Hapus akun panitia">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                fill="none" stroke="currentColor" stroke-width="2.2"
                                                viewBox="0 0 24 24" aria-hidden="true">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                </path>
                                                <line x1="10" y1="11" x2="10" y2="17">
                                                </line>
                                                <line x1="14" y1="11" x2="14" y2="17">
                                                </line>
                                            </svg>
                                            <span>Hapus Panitia</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="eoe-empty-panitia">
                            <div class="eoe-empty-panitia-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <h3>Belum Ada Panitia Terdaftar</h3>
                            <p>Tambahkan akun panitia agar tim lapangan dapat membantu verifikasi tiket saat acara
                                berlangsung.</p>
                            <button type="button" class="eoe-btn eoe-btn-yellow" onclick="openAddPanitiaModal()">
                                + Add Panitia Sekarang
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Section Daftar Presensi Pembeli (Di dalam .eoe-detail-container) -->
            @php
                $daftarPresensi = $presensi ?? collect();
                $jumlahHadir = $daftarPresensi->where('status', 'used')->count();
            @endphp

            <div class="eoe-panitia-section" id="daftar-presensi" style="margin-top: 28px;">
                <div class="eoe-tickets-header">
                    <div>
                        <h2 class="eoe-section-title">Presensi &amp; Kehadiran Pembeli</h2>
                        <p class="eoe-section-subtitle">Data kehadiran pengunjung berdasarkan hasil scan QR tiket oleh
                            panitia di
                            pintu masuk.</p>
                    </div>

                    <div class="eoe-panitia-header-actions">
                        <span class="eoe-ticket-count-pill">
                            {{ $jumlahHadir }} / {{ count($daftarPresensi) }} Hadir
                        </span>
                    </div>
                </div>

                @if (count($daftarPresensi) > 0)
                    <div style="overflow-x: auto; background: #fff; border: 1px solid #e4e4e7; border-radius: 12px;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                            <thead>
                                <tr style="background: #f8fafc; border-bottom: 1px solid #e4e4e7; color: #475569;">
                                    <th style="padding: 14px 16px;">Kode Tiket</th>
                                    <th style="padding: 14px 16px;">Nama Pembeli</th>
                                    <th style="padding: 14px 16px;">Kategori Tiket</th>
                                    <th style="padding: 14px 16px;">Waktu Check-in</th>
                                    <th style="padding: 14px 16px; text-align: center;">Status Presensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($daftarPresensi as $peserta)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td
                                            style="padding: 14px 16px; font-family: monospace; font-weight: 700; color: #1e293b;">
                                            {{ $peserta->ticket_code }}
                                        </td>
                                        <td style="padding: 14px 16px;">
                                            <div style="font-weight: 600; color: #0f172a;">
                                                {{ $peserta->attendee_name ?? '-' }}
                                            </div>
                                            <div style="font-size: 12px; color: #64748b;">
                                                {{ $peserta->attendee_email ?? '-' }}
                                            </div>
                                        </td>
                                        <td style="padding: 14px 16px; color: #334155;">
                                            {{ $peserta->ticket_type_name }}
                                        </td>
                                        <td style="padding: 14px 16px; color: #475569;">
                                            @if ($peserta->checked_in_at)
                                                {{ \Carbon\Carbon::parse($peserta->checked_in_at)->format('d M Y, H:i:s') }}
                                                WIB
                                            @else
                                                <span style="color: #94a3b8;">-</span>
                                            @endif
                                        </td>
                                        <td style="padding: 14px 16px; text-align: center;">
                                            @if ($peserta->status === 'used')
                                                <span
                                                    style="display: inline-block; background: #dcfce7; color: #166534; padding: 5px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;">
                                                    ✓ Hadir
                                                </span>
                                            @else
                                                <span
                                                    style="display: inline-block; background: #f1f5f9; color: #64748b; padding: 5px 12px; border-radius: 999px; font-size: 12px; font-weight: 600;">
                                                    Belum Hadir
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="eoe-empty-panitia">
                        <div class="eoe-empty-panitia-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 11l3 3L22 4"></path>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                        </div>
                        <h3>Belum Ada Data Presensi</h3>
                        <p>Daftar pembeli yang hadir akan otomatis tercatat di sini setelah tiket di-scan oleh panitia.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Modal Tambah Panitia -->
    <div class="eoe-modal-backdrop" id="addPanitiaModal" data-has-errors="{{ $errors->any() ? '1' : '0' }}"
        role="dialog" aria-modal="true" aria-labelledby="addPanitiaModalTitle">
        <div class="eoe-modal-card">
            <div class="eoe-modal-header">
                <div>
                    <span class="eoe-modal-eyebrow">MANAJEMEN TIM EVENT</span>
                    <h3 id="addPanitiaModalTitle" class="eoe-modal-title">Tambah Akun Panitia</h3>
                </div>
                <button type="button" class="eoe-modal-close" aria-label="Tutup Modal"
                    onclick="closeAddPanitiaModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                        stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form action="{{ $routePanitiaStore }}" method="POST" class="eoe-modal-form">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <div class="eoe-form-group">
                    <label for="panitia_name" class="eoe-form-label">Nama Lengkap Panitia <span
                            class="eoe-req">*</span></label>
                    <input type="text" id="panitia_name" name="name" class="eoe-form-input"
                        value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required>
                </div>

                <div class="eoe-form-group">
                    <label for="panitia_email" class="eoe-form-label">Alamat Email Login <span
                            class="eoe-req">*</span></label>
                    <input type="email" id="panitia_email" name="email" class="eoe-form-input"
                        value="{{ old('email') }}" placeholder="panitia@mivent.id" required>
                </div>

                <div class="eoe-form-group">
                    <label for="panitia_password" class="eoe-form-label">Password Akun <span
                            class="eoe-req">*</span></label>
                    <input type="password" id="panitia_password" name="password" class="eoe-form-input" minlength="6"
                        placeholder="Minimal 6 karakter" required>
                    <span class="eoe-form-hint">Password ini akan digunakan panitia untuk masuk ke sistem check-in.</span>
                </div>

                <div class="eoe-modal-footer">
                    <button type="button" class="eoe-btn eoe-btn-ghost" onclick="closeAddPanitiaModal()">
                        Batal
                    </button>
                    <button type="submit" class="eoe-btn eoe-btn-yellow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                            stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Simpan Panitia</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddPanitiaModal() {
            const modal = document.getElementById('addPanitiaModal');
            if (!modal) return;
            modal.classList.add('is-open');
            const firstInput = document.getElementById('panitia_name');
            if (firstInput) setTimeout(() => firstInput.focus(), 80);
        }

        function closeAddPanitiaModal() {
            const modal = document.getElementById('addPanitiaModal');
            if (!modal) return;
            modal.classList.remove('is-open');
        }

        function confirmDeletePanitia(panitiaId, panitiaName) {
            const form = document.getElementById('delete-panitia-form-' + panitiaId);
            if (!form) return;

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Akun Panitia?',
                    text: 'Akun panitia "' + panitiaName + '" akan dihapus permanen dari sistem.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#8C8C9E',
                    confirmButtonText: 'Ya, Hapus Panitia',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus akun panitia "' + panitiaName + '"?')) {
                    form.submit();
                }
            }
        }

        // Tutup modal saat klik area luar atau menekan tombol Escape
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('addPanitiaModal');
            if (modal && e.target === modal) {
                closeAddPanitiaModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddPanitiaModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('addPanitiaModal');
            if (modal && modal.dataset.hasErrors === '1') {
                openAddPanitiaModal();
            }
        });
    </script>
@endsection

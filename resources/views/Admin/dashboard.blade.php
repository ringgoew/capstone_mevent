@extends('layouts.admin')
@section('title', 'Dashboard Admin — M!VENT')
@section('page-title', 'Dashboard Administrator')
@section('page-subtitle', 'Selamat datang di panel kendali platform M!VENT.')

@section('content')

@php
$totalEvent = $data['total_event'] ?? 0;
$eventPending = $data['event_pending'] ?? 0;
$totalEo = $data['total_eo'] ?? 0;
$totalTiket = $data['total_tiket'] ?? 0;
$pendingRatio = $totalEvent > 0 ? min(100, round(($eventPending / $totalEvent) * 100)) : ($eventPending > 0 ? 50 : 12);
@endphp

<!-- 1. STATISTIK KPI PLATFORM -->
<section class="stats" aria-label="Statistik Platform">
    <!-- Total Event -->
    <div class="stat-card">
        <div>
            <div class="stat-card-top">
                <span class="stat-title">Total Event</span>
                <span class="stat-icon-box" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </span>
            </div>
            <div class="stat-number">{{ number_format($totalEvent, 0, ',', '.') }}</div>
            <div class="stat-caption">Seluruh event terdaftar di sistem</div>
        </div>
        <div class="stat-progress">
            <div class="stat-progress-bar" style="width: 78%;"></div>
        </div>
    </div>

    <!-- Event Menunggu Review -->
    <div class="stat-card stat-highlight">
        <div>
            <div class="stat-card-top">
                <span class="stat-title">Event Menunggu Review</span>
                <span class="stat-pill-tag">Perlu Tinjauan</span>
            </div>
            <div class="stat-number warning">{{ number_format($eventPending, 0, ',', '.') }}</div>
            <div class="stat-caption">Pengajuan event menanti persetujuan</div>
        </div>
        <div class="stat-progress">
            <div class="stat-progress-bar amber" style="width: {{ $pendingRatio }}%;"></div>
        </div>
    </div>

    <!-- Total Event Organizer -->
    <div class="stat-card">
        <div>
            <div class="stat-card-top">
                <span class="stat-title">Total Event Organizer</span>
                <span class="stat-icon-box" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </span>
            </div>
            <div class="stat-number">{{ number_format($totalEo, 0, ',', '.') }}</div>
            <div class="stat-caption">Mitra penyelenggara terdaftar</div>
        </div>
        <div class="stat-progress">
            <div class="stat-progress-bar navy" style="width: 68%;"></div>
        </div>
    </div>

    <!-- Tiket Terjual -->
    <div class="stat-card">
        <div>
            <div class="stat-card-top">
                <span class="stat-title">Tiket Terjual</span>
                <span class="stat-pill-tag info">Transaksi</span>
            </div>
            <div class="stat-number info">{{ number_format($totalTiket, 0, ',', '.') }}</div>
            <div class="stat-caption">Akumulasi tiket terjual publik</div>
        </div>
        <div class="stat-progress">
            <div class="stat-progress-bar blue" style="width: 84%;"></div>
        </div>
    </div>
</section>

<!-- 2. MODUL KELOLA & AKSI CEPAT ADMIN -->
<section class="menu-container" aria-label="Menu Kelola Admin">
    <!-- Card 1: Verifikasi EO (KYC) -->
    <div class="menu-card">
        <div>
            <div class="menu-card-header">
                <span class="menu-icon-wrap" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <path d="m9 15 2 2 4-4"></path>
                    </svg>
                </span>
                <span class="badge draft">Legalitas EO</span>
            </div>
            <h3>Verifikasi EO (KYC)</h3>
            <p>
                Periksa dokumen identitas (KTP, NPWP) dan rekening bank yang diajukan oleh Event Organizer sebelum mengaktifkan hak publikasi.
            </p>
        </div>
        <div class="menu-card-footer">
            <a href="{{ route('admin.kyc.index') }}" class="btn btn-navy btn-block">
                <span>Periksa Dokumen KYC</span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>

    <!-- Card 2: Verifikasi Event -->
    <div class="menu-card">
        <div>
            <div class="menu-card-header">
                <span class="menu-icon-wrap yellow" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                </span>
                @if ($eventPending > 0)
                <span class="badge pending">{{ $eventPending }} Menunggu</span>
                @else
                <span class="badge verified">Semua Terverifikasi</span>
                @endif
            </div>
            <h3>Verifikasi Event</h3>
            <p>
                Tinjau kelayakan detail event, jadwal pertunjukan, kategori harga, dan kuota tiket sebelum diterbitkan ke katalog publik.
            </p>
        </div>
        <div class="menu-card-footer">
            <a href="{{ route('admin.event-verification.index') }}" class="btn btn-yellow btn-block">
                <span>Periksa Pengajuan Event</span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>

    <!-- Card 3: Laporan & Monitoring -->
    <div class="menu-card">
        <div>
            <div class="menu-card-header">
                <span class="menu-icon-wrap green" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18"></path>
                        <path d="m19 9-5 5-4-4-3 3"></path>
                    </svg>
                </span>
                <span class="badge verified">Live Sync</span>
            </div>
            <h3>Laporan &amp; Monitoring</h3>
            <p>
                Pantau arus transaksi tiket, status publikasi event, dan tingkat kepatuhan penyelenggara di seluruh ekosistem M!VENT.
            </p>
        </div>
        <div class="menu-card-footer">
            <span class="realtime-indicator">
                <span class="status-dot"></span>
                Real-time monitoring aktif
            </span>
            <a href="#ringkasan-operasional" class="btn btn-gray btn-sm">Ringkasan</a>
        </div>
    </div>
</section>

<!-- 3. RINGKASAN OPERASIONAL & ANTREAN MODERASI -->
<section class="card" id="ringkasan-operasional">
    <div class="card-header-flex">
        <div class="card-title-group">
            <h2>Ringkasan Operasional &amp; Antrean Moderasi</h2>
            <p>Ikhtisar cepat status verifikasi penyelenggara dan publikasi event pada platform.</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('admin.kyc.index') }}" class="btn btn-gray btn-sm">Kelola KYC</a>
            <a href="{{ route('admin.event-verification.index') }}" class="btn btn-navy btn-sm">Antrean Event</a>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Modul Moderasi</th>
                    <th>Deskripsi Tugas</th>
                    <th>Volume Data</th>
                    <th>Status Antrean</th>
                    <th style="text-align: right;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="table-module-cell">
                            <span class="table-module-icon" aria-hidden="true">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </span>
                            <div>
                                <div class="table-module-title">Kurasi &amp; Verifikasi Event</div>
                                <div class="table-module-sub">Validasi poster, jadwal &amp; kuota tiket</div>
                            </div>
                        </div>
                    </td>
                    <td>Peninjauan kelayakan event sebelum tayang di halaman publik</td>
                    <td><strong>{{ number_format($totalEvent, 0, ',', '.') }}</strong> Total Event</td>
                    <td>
                        @if ($eventPending > 0)
                        <span class="badge pending">{{ $eventPending }} Menunggu Review</span>
                        @else
                        <span class="badge verified">Antrean Bersih</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.event-verification.index') }}" class="btn btn-yellow btn-sm">Tinjau</a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="table-module-cell">
                            <span class="table-module-icon" aria-hidden="true">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <polyline points="16 11 18 13 22 9"></polyline>
                                </svg>
                            </span>
                            <div>
                                <div class="table-module-title">Verifikasi Identitas EO (KYC)</div>
                                <div class="table-module-sub">Validasi KTP, NPWP &amp; akun rekening</div>
                            </div>
                        </div>
                    </td>
                    <td>Pemeriksaan legalitas penyelenggara acara dan mitra EO</td>
                    <td><strong>{{ number_format($totalEo, 0, ',', '.') }}</strong> Terdaftar</td>
                    <td>
                        <span class="badge published">Aktif</span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.kyc.index') }}" class="btn btn-navy btn-sm">Periksa</a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="table-module-cell">
                            <span class="table-module-icon" aria-hidden="true">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                                    <path d="M13 5v2" />
                                    <path d="M13 17v2" />
                                    <path d="M13 11v2" />
                                </svg>
                            </span>
                            <div>
                                <div class="table-module-title">Performa Penjualan Tiket</div>
                                <div class="table-module-sub">Monitoring transaksi tiket tervalidasi</div>
                            </div>
                        </div>
                    </td>
                    <td>Akumulasi penjualan tiket seluruh event terverifikasi</td>
                    <td><strong>{{ number_format($totalTiket, 0, ',', '.') }}</strong> Tiket Terjual</td>
                    <td>
                        <span class="badge verified">Tersinkronisasi</span>
                    </td>
                    <td style="text-align: right;">
                        <span style="font-size: 12.5px; color: var(--color-muted-grey); font-weight: 600;">Otomatis</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<!-- 4. SYSTEM NOTICE BANNER -->
<div class="system-notice" role="status">
    <div class="system-notice-left">
        <span class="system-notice-icon" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
        </span>
        <span>
            <strong>Panduan Moderasi:</strong> Pastikan seluruh dokumen KYC Event Organizer telah berstatus <em>Verified</em> sebelum menyetujui publikasi event berbayar.
        </span>
    </div>
    <span style="font-size: 12px; color: var(--color-muted-grey); font-weight: 600;">
        M!VENT Admin v2.0
    </span>
</div>

@endsection
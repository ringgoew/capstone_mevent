@extends('layouts.eo')

@section('title', 'Laporan Penjualan - ' . $event->title . ' - M!VENT')

@section('content')
<<<<<<< HEAD
<div style="padding: 10px 0;">
    <!-- HEADER LAPORAN -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="margin: 0; font-size: 24px;">Laporan Penjualan: {{ $event->title }}</h1>
            <p style="color: #64748b; margin-top: 5px; font-size: 14px;">
                📍 {{ $event->venue_name ?? 'Lokasi belum diatur' }} &nbsp;|&nbsp; 
                📅 {{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->translatedFormat('d F Y, H:i') : '-' }}
            </p>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('eo.reports.index') }}" class="btn" style="background: #64748b; color: #fff; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
                ← Kembali
            </a>
            <button onclick="window.print()" class="btn" style="background: #1e293b; color: #fff; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                🖨️ Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">

    <!-- 1. RINGKASAN PENJUALAN (CARDS) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0;">
            <p style="color: #64748b; font-size: 13px; margin: 0 0 8px 0; text-transform: uppercase; font-weight: 600;">Total Transaksi Sukses</p>
            <h2 style="color: #2563eb; margin: 0; font-size: 26px;">
                {{ $salesSummary->total_transactions ?? 0 }} 
                <span style="font-size: 14px; font-weight: normal; color: #64748b;">pesanan</span>
            </h2>
        </div>

        <div style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0;">
            <p style="color: #64748b; font-size: 13px; margin: 0 0 8px 0; text-transform: uppercase; font-weight: 600;">Total Pendapatan (Gross)</p>
            <h2 style="color: #16a34a; margin: 0; font-size: 26px;">
                Rp {{ number_format($salesSummary->gross_revenue ?? 0, 0, ',', '.') }}
            </h2>
        </div>

        <div style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0;">
            <p style="color: #64748b; font-size: 13px; margin: 0 0 8px 0; text-transform: uppercase; font-weight: 600;">Pendapatan Bersih (Net)</p>
            <h2 style="color: #0f172a; margin: 0; font-size: 26px;">
                Rp {{ number_format($salesSummary->net_revenue ?? 0, 0, ',', '.') }}
            </h2>
        </div>
    </div>

    <!-- 2. PERFORMA JENIS TIKET (TABEL) -->
    <div style="background: #fff; padding: 25px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 30px; overflow-x: auto;">
        <h3 style="margin: 0 0 15px 0; font-size: 18px;">Performa Jenis Tiket</h3>
        
        <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 600px;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 13px;">
                    <th style="padding: 12px;">Jenis Tiket</th>
                    <th style="padding: 12px;">Harga Satuan</th>
                    <th style="padding: 12px;">Total Kuota</th>
                    <th style="padding: 12px;">Terjual (Sold)</th>
                    <th style="padding: 12px;">Tersedia (Sisa)</th>
                    <th style="padding: 12px;">Pendapatan Kategori</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ticketPerformances as $tp)
                <tr style="border-bottom: 1px solid #e2e8f0; font-size: 14px;">
                    <td style="padding: 12px; font-weight: 600;">{{ $tp->ticket_name }}</td>
                    <td style="padding: 12px;">Rp {{ number_format($tp->price, 0, ',', '.') }}</td>
                    <td style="padding: 12px;">{{ $tp->quota }}</td>
                    <td style="padding: 12px; color: #16a34a; font-weight: bold;">{{ $tp->sold_quota }}</td>
                    <td style="padding: 12px; color: #2563eb; font-weight: 600;">{{ $tp->available_quota }}</td>
                    <td style="padding: 12px; font-weight: bold;">Rp {{ number_format($tp->total_revenue_by_type, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 20px; text-align: center; color: #64748b;">Belum ada jenis tiket untuk event ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 3. DAFTAR KEHADIRAN PESERTA / CHECK-IN LOG (HASIL SCAN PANITIA) -->
    <div style="background: #fff; padding: 25px; border-radius: 10px; border: 1px solid #e2e8f0; overflow-x: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0; font-size: 18px;">Daftar Kehadiran Peserta (Check-In)</h3>
            <span style="font-size: 13px; color: #64748b;">
                Hadir: <strong>{{ collect($attendees ?? [])->where('check_in_status', 'used')->count() }}</strong> / {{ count($attendees ?? []) }} peserta
            </span>
        </div>
        
        <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 650px;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 13px;">
                    <th style="padding: 12px;">Kode Tiket</th>
                    <th style="padding: 12px;">Nama Peserta</th>
                    <th style="padding: 12px;">Jenis Tiket</th>
                    <th style="padding: 12px;">Status Kehadiran</th>
                    <th style="padding: 12px;">Waktu Check-In</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendees ?? [] as $att)
                <tr style="border-bottom: 1px solid #e2e8f0; font-size: 14px;">
                    <td style="padding: 12px; font-family: monospace; font-weight: bold; color: #334155;">{{ $att->ticket_code }}</td>
                    <td style="padding: 12px;">
                        <div style="font-weight: 600;">{{ $att->attendee_name }}</div>
                        <div style="font-size: 12px; color: #64748b;">{{ $att->attendee_email }}</div>
                    </td>
                    <td style="padding: 12px;">{{ $att->ticket_type }}</td>
                    <td style="padding: 12px;">
                        @if ($att->check_in_status === 'used')
                            <span style="padding: 4px 10px; background: #dcfce7; color: #166534; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                ✅ Hadir
                            </span>
                        @else
                            <span style="padding: 4px 10px; background: #f1f5f9; color: #64748b; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                ⏳ Belum Hadir
                            </span>
                        @endif
                    </td>
                    <td style="padding: 12px; font-size: 13px; color: #475569;">
                        {{ $att->checked_in_at ? \Carbon\Carbon::parse($att->checked_in_at)->format('d M Y, H:i:s') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 25px; text-align: center; color: #64748b;">Belum ada tiket yang terjual untuk event ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
=======
<div class="eor-report">

    <!-- Top Action Bar (Back + Print PDF) -->
    <div class="eor-detail-nav no-print">
        <a href="{{ route('eo.reports.index') }}" class="eor-btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kembali ke Daftar Laporan</span>
        </a>

        <button type="button" onclick="window.print()" class="eor-btn-print">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
            </svg>
            <span>Cetak / Ekspor PDF</span>
        </button>
    </div>

    <!-- Event Ticket-Stub Header Card -->
    <div class="eor-event-banner-card">
        <div class="eor-event-banner-main">
            <span class="eor-eyebrow">AUDIT PENJUALAN EVENT</span>
            <h1>{{ $event->title }}</h1>

            <div class="eor-event-meta-row">
                <span class="eor-meta-chip">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <span>{{ $event->venue_name }}</span>
                </span>

                <span class="eor-meta-sep" aria-hidden="true">•</span>

                <span class="eor-meta-chip">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('l, d F Y - H:i') }} WIB</span>
                </span>
            </div>
        </div>

        <div class="eor-ticket-perforation" aria-hidden="true"></div>

        <div class="eor-event-banner-foot">
            <span>Penyelenggara: <strong>{{ session('eo_nama', 'Event Organizer') }}</strong></span>
            <span>Dicetak pada: <strong>{{ now()->translatedFormat('d M Y, H:i') }} WIB</strong></span>
        </div>
    </div>

    <!-- 3 Metric KPI Cards -->
    <div class="eor-kpi-grid">
        <div class="eor-kpi-card">
            <div class="eor-kpi-top">
                <span class="eor-kpi-label">Total Transaksi Sukses</span>
                <span class="eor-kpi-icon eor-icon-blue" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </span>
            </div>
            <div class="eor-kpi-value eor-val-blue">
                {{ number_format($salesSummary->total_transactions ?? 0, 0, ',', '.') }}
                <span class="eor-kpi-unit">pesanan</span>
            </div>
            <div class="eor-kpi-foot">
                <span class="eor-kpi-tag eor-tag-blue">Sukses</span>
                <span class="eor-kpi-hint">Telah dibayar &amp; terverifikasi</span>
            </div>
        </div>

        <div class="eor-kpi-card">
            <div class="eor-kpi-top">
                <span class="eor-kpi-label">Total Pendapatan (Gross)</span>
                <span class="eor-kpi-icon eor-icon-green" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </span>
            </div>
            <div class="eor-kpi-value eor-val-green">
                Rp {{ number_format($salesSummary->gross_revenue ?? 0, 0, ',', '.') }}
            </div>
            <div class="eor-kpi-foot">
                <span class="eor-kpi-tag eor-tag-green">Gross</span>
                <span class="eor-kpi-hint">Akumulasi penjualan sebelum potongan</span>
            </div>
        </div>

        <div class="eor-kpi-card eor-kpi-card-navy">
            <div class="eor-kpi-top">
                <span class="eor-kpi-label">Pendapatan Bersih (Net)</span>
                <span class="eor-kpi-icon eor-icon-yellow" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                        <circle cx="12" cy="12" r="2"></circle>
                        <path d="M6 12h.01M18 12h.01"></path>
                    </svg>
                </span>
            </div>
            <div class="eor-kpi-value eor-val-yellow">
                Rp {{ number_format($salesSummary->net_revenue ?? 0, 0, ',', '.') }}
            </div>
            <div class="eor-kpi-foot">
                <span class="eor-kpi-tag eor-tag-yellow">Net Payout</span>
                <span class="eor-kpi-hint">Hasil estimasi pencairan EO</span>
            </div>
        </div>
    </div>

    <!-- Ticket Categories Performance Table -->
    <div class="eor-panel">
        <div class="eor-panel-head">
            <div>
                <h2>Performa Penjualan Kategori Tiket</h2>
                <p>Rincian serapan kuota dan pendapatan berdasarkan masing-masing kelas tiket.</p>
            </div>
            <span class="eor-category-count">{{ count($ticketPerformances) }} Kategori Tiket</span>
        </div>

        @php
        $totalQuotaAll = 0;
        $totalSoldAll = 0;
        $totalAvailAll = 0;
        $totalRevAll = 0;
        @endphp

        <div class="eor-table-wrap">
            <table class="eor-table">
                <thead>
                    <tr>
                        <th>Jenis Tiket</th>
                        <th>Harga Satuan</th>
                        <th>Total Kuota</th>
                        <th>Terjual</th>
                        <th>Sisa Kuota</th>
                        <th>Penjualan</th>
                        <th class="eor-th-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ticketPerformances as $tp)
                    @php
                    $pct = $tp->quota > 0 ? round(($tp->sold_quota / $tp->quota) * 100) : 0;
                    $totalQuotaAll += (int) $tp->quota;
                    $totalSoldAll += (int) $tp->sold_quota;
                    $totalAvailAll += (int) $tp->available_quota;
                    $totalRevAll += (float) $tp->total_revenue_by_type;
                    @endphp
                    <tr>
                        <td>
                            <span class="eor-ticket-type-name">{{ $tp->ticket_name }}</span>
                        </td>
                        <td class="eor-td-price">
                            @if((float) $tp->price <= 0)
                                <span class="eor-free-tag">FREE</span>
                                @else
                                Rp {{ number_format($tp->price, 0, ',', '.') }}
                                @endif
                        </td>
                        <td class="eor-td-quota">
                            {{ number_format($tp->quota, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="eor-sold-badge">
                                {{ number_format($tp->sold_quota, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="eor-td-avail">
                            {{ number_format($tp->available_quota, 0, ',', '.') }}
                        </td>
                        <td class="eor-td-progress">
                            <div class="eor-progress-cell">
                                <div class="eor-progress-track">
                                    <div class="eor-progress-fill" style="width: {{ min($pct, 100) }}%;"></div>
                                </div>
                                <span class="eor-progress-pct">{{ $pct }}%</span>
                            </div>
                        </td>
                        <td class="eor-td-revenue eor-td-right">
                            Rp {{ number_format($tp->total_revenue_by_type, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="eor-empty-cell">
                            <div class="eor-empty-box eor-empty-compact">
                                <h3>Belum Ada Kategori Tiket</h3>
                                <p>Belum ada jenis tiket yang terdaftar untuk event ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                @if(count($ticketPerformances) > 0)
                @php
                $overallPct = $totalQuotaAll > 0 ? round(($totalSoldAll / $totalQuotaAll) * 100) : 0;
                @endphp
                <tfoot>
                    <tr class="eor-table-foot">
                        <td colspan="2">Total Keseluruhan Tiket</td>
                        <td>{{ number_format($totalQuotaAll, 0, ',', '.') }}</td>
                        <td>{{ number_format($totalSoldAll, 0, ',', '.') }}</td>
                        <td>{{ number_format($totalAvailAll, 0, ',', '.') }}</td>
                        <td>{{ $overallPct }}% Terjual</td>
                        <td class="eor-td-right">Rp {{ number_format($totalRevAll, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
>>>>>>> payout
    </div>

</div>
@endsection
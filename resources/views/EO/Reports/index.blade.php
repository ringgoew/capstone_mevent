@extends('layouts.eo')

@section('title', 'Laporan Penjualan - M!VENT')

@section('content')
<div class="eor-report">

    <!-- Topbar Header -->
    <div class="eor-topbar">
        <div class="eor-topbar-title">
            <span class="eor-eyebrow">ANALITIK &amp; KEUANGAN EO</span>
            <h1>Laporan Penjualan &amp; Pendapatan</h1>
            <p class="eor-subtitle">Rekapitulasi akumulasi transaksi tiket dan performa finansial seluruh event Anda.</p>
        </div>

        <div class="eor-profile-pill">
            <span class="eor-profile-avatar" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </span>
            <span>{{ session('eo_nama', 'Event Organizer') }}</span>
        </div>
    </div>

    <!-- 3 Metrics KPI Cards -->
    <div class="eor-kpi-grid">
        <div class="eor-kpi-card">
            <div class="eor-kpi-top">
                <span class="eor-kpi-label">Total Transaksi Sukses</span>
                <span class="eor-kpi-icon eor-icon-blue" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                        <line x1="2" y1="10" x2="22" y2="10"></line>
                    </svg>
                </span>
            </div>
            <div class="eor-kpi-value eor-val-blue">
                {{ number_format($overallSummary->total_transactions ?? 0, 0, ',', '.') }}
            </div>
            <div class="eor-kpi-foot">
                <span class="eor-kpi-tag eor-tag-blue">Terverifikasi</span>
                <span class="eor-kpi-hint">Pesanan berhasil dibayar</span>
            </div>
        </div>

        <div class="eor-kpi-card">
            <div class="eor-kpi-top">
                <span class="eor-kpi-label">Akumulasi Tiket Terjual</span>
                <span class="eor-kpi-icon eor-icon-purple" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                        <path d="M13 5v2"></path>
                        <path d="M13 17v2"></path>
                        <path d="M13 11v2"></path>
                    </svg>
                </span>
            </div>
            <div class="eor-kpi-value eor-val-purple">
                {{ number_format($totalTicketsSold ?? 0, 0, ',', '.') }}
                <span class="eor-kpi-unit">tiket</span>
            </div>
            <div class="eor-kpi-foot">
                <span class="eor-kpi-tag eor-tag-purple">Akumulasi</span>
                <span class="eor-kpi-hint">Dari seluruh event aktif</span>
            </div>
        </div>

        <div class="eor-kpi-card eor-kpi-card-navy">
            <div class="eor-kpi-top">
                <span class="eor-kpi-label">Total Pendapatan (Gross)</span>
                <span class="eor-kpi-icon eor-icon-yellow" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </span>
            </div>
            <div class="eor-kpi-value eor-val-yellow">
                Rp {{ number_format($overallSummary->gross_revenue ?? 0, 0, ',', '.') }}
            </div>
            <div class="eor-kpi-foot">
                <span class="eor-kpi-tag eor-tag-yellow">Gross Revenue</span>
                <span class="eor-kpi-hint">Pendapatan kotor akumulatif</span>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="eor-panel">
        <div class="eor-panel-head">
            <div>
                <h2>Performa Per Event</h2>
                <p>Klik tombol <strong>Detail Laporan</strong> untuk melihat audit penjualan tiket per kategori.</p>
            </div>

            @if(count($eventsReport) > 0)
            <div class="eor-toolbar">
                <div class="eor-filter-group" role="tablist" aria-label="Filter status laporan event">
                    <button type="button" class="eor-filter-pill is-active" data-status="all">Semua</button>
                    <button type="button" class="eor-filter-pill" data-status="published">Published</button>
                    <button type="button" class="eor-filter-pill" data-status="pending">Pending</button>
                    <button type="button" class="eor-filter-pill" data-status="other">Draft / Ditolak</button>
                </div>

                <div class="eor-search-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="search" id="eorSearchInput" class="eor-search-input" placeholder="Cari nama event...">
                </div>
            </div>
            @endif
        </div>

        <div class="eor-table-wrap">
            <table class="eor-table" id="eorEventsTable">
                <thead>
                    <tr>
                        <th>Nama Event</th>
                        <th>Jadwal Mulai</th>
                        <th>Status</th>
                        <th>Tiket Terjual</th>
                        <th>Total Pendapatan</th>
                        <th class="eor-th-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($eventsReport as $ev)
                    @php
                    $st = strtolower($ev->status ?? 'draft');
                    $badgeClass = match($st) {
                    'published' => 'published',
                    'pending_review', 'pending' => 'pending',
                    'rejected' => 'rejected',
                    default => 'draft'
                    };
                    $badgeLabel = match($st) {
                    'published' => 'Published',
                    'pending_review', 'pending' => 'Pending Review',
                    'rejected' => 'Rejected',
                    default => 'Draft'
                    };
                    $filterGroup = match($st) {
                    'published' => 'published',
                    'pending_review', 'pending' => 'pending',
                    default => 'other'
                    };
                    @endphp
                    <tr class="eor-event-row"
                        data-status="{{ $filterGroup }}"
                        data-title="{{ strtolower($ev->event_name) }}">
                        <td>
                            <div class="eor-event-cell">
                                <span class="eor-event-monogram" aria-hidden="true">
                                    {{ strtoupper(mb_substr($ev->event_name, 0, 1)) }}
                                </span>
                                <span class="eor-event-name">{{ $ev->event_name }}</span>
                            </div>
                        </td>
                        <td class="eor-td-muted">
                            {{ $ev->start_time ? \Carbon\Carbon::parse($ev->start_time)->translatedFormat('d M Y, H:i') : '-' }}
                        </td>
                        <td>
                            <span class="eor-status-pill is-{{ $badgeClass }}">
                                {{ $badgeLabel }}
                            </span>
                        </td>
                        <td>
                            <span class="eor-sold-badge">
                                {{ number_format($ev->total_sold ?? 0, 0, ',', '.') }} tiket
                            </span>
                        </td>
                        <td class="eor-td-revenue">
                            Rp {{ number_format($ev->event_revenue ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="eor-td-center">
                            <a href="{{ route('eo.reports.sales', $ev->id) }}" class="eor-btn-detail">
                                <span>Detail Laporan</span>
                                <span class="eor-arrow" aria-hidden="true">→</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="eor-empty-cell">
                            <div class="eor-empty-box">
                                <div class="eor-empty-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <line x1="18" y1="20" x2="18" y2="10"></line>
                                        <line x1="12" y1="20" x2="12" y2="4"></line>
                                        <line x1="6" y1="20" x2="6" y2="14"></line>
                                    </svg>
                                </div>
                                <h3>Belum Ada Data Penjualan</h3>
                                <p>Laporan performa akan muncul di sini setelah tiket event Anda mulai terjual.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    @if(count($eventsReport) > 0)
                    <tr id="eorNoMatchRow" style="display: none;">
                        <td colspan="6" class="eor-empty-cell">
                            <div class="eor-empty-box eor-empty-compact">
                                <h3>Event Tidak Ditemukan</h3>
                                <p>Tidak ada laporan event yang cocok dengan filter atau kata kunci pencarian Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>

@if(count($eventsReport) > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterPills = document.querySelectorAll('.eor-filter-pill');
        const searchInput = document.getElementById('eorSearchInput');
        const rows = document.querySelectorAll('.eor-event-row');
        const noMatchRow = document.getElementById('eorNoMatchRow');
        let activeStatus = 'all';

        function applyReportFilters() {
            const keyword = (searchInput ? searchInput.value : '').trim().toLowerCase();
            let visibleCount = 0;

            rows.forEach(function(row) {
                const matchStatus = activeStatus === 'all' || row.dataset.status === activeStatus;
                const matchText = !keyword || (row.dataset.title || '').includes(keyword);

                if (matchStatus && matchText) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noMatchRow) {
                noMatchRow.style.display = visibleCount === 0 ? '' : 'none';
            }
        }

        filterPills.forEach(function(pill) {
            pill.addEventListener('click', function() {
                filterPills.forEach(function(btn) {
                    btn.classList.remove('is-active');
                });
                pill.classList.add('is-active');
                activeStatus = pill.dataset.status || 'all';
                applyReportFilters();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', applyReportFilters);
        }
    });
</script>
@endif
@endsection
@extends('layouts.main')

@section('title', 'Laporan Penjualan - ' . $event->title)

@section('content')
<div class="container" style="padding: 30px 0;">
    <h2>Laporan Penjualan: {{ $event->title }}</h2>
    <p style="color: #666;">Venue: {{ $event->venue_name }} | Mulai: {{ $event->start_time }}</p>

    <hr style="margin: 20px 0;">

    <!-- 1. RINGKASAN PENJUALAN (CARDS) -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="color: #666; margin-bottom: 5px;">Total Transaksi Sukses</h4>
            <h2 style="color: #2563eb; margin: 0;">{{ $salesSummary->total_transactions }} <span style="font-size: 14px; font-weight: normal;">pesanan</span></h2>
        </div>

        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="color: #666; margin-bottom: 5px;">Total Pendapatan (Gross)</h4>
            <h2 style="color: #16a34a; margin: 0;">Rp {{ number_format($salesSummary->gross_revenue, 0, ',', '.') }}</h2>
        </div>

        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="color: #666; margin-bottom: 5px;">Pendapatan Bersih (Net)</h4>
            <h2 style="color: #0f172a; margin: 0;">Rp {{ number_format($salesSummary->net_revenue, 0, ',', '.') }}</h2>
        </div>
    </div>

    <!-- 2. PERFORMA JENIS TIKET (TABEL) -->
    <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <h3 style="margin-bottom: 15px;">Performa Jenis Tiket</h3>
        
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
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
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px; font-weight: bold;">{{ $tp->ticket_name }}</td>
                    <td style="padding: 12px;">Rp {{ number_format($tp->price, 0, ',', '.') }}</td>
                    <td style="padding: 12px;">{{ $tp->quota }}</td>
                    <td style="padding: 12px; color: #16a34a; font-weight: bold;">{{ $tp->sold_quota }}</td>
                    <td style="padding: 12px; color: #2563eb;">{{ $tp->available_quota }}</td>
                    <td style="padding: 12px; font-weight: bold;">Rp {{ number_format($tp->total_revenue_by_type, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 20px; text-align: center; color: #666;">Belum ada jenis tiket untuk event ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #334155; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Cetak / Simpan Laporan</button>
    </div>
</div>
@endsection 
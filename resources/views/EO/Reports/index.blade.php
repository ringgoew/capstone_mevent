@extends('layouts.eo')

@section('title', 'Laporan Keseluruhan - M!VENT')

@section('content')
<div class="container" style="padding: 20px 0;">
    <h2>Laporan Keseluruhan Event</h2>
    <p style="color: #666; margin-bottom: 25px;">Rekapitulasi total pendapatan dan performa seluruh event yang Anda kelola.</p>

    <!-- CARDS RINGKASAN KESELURUHAN -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="color: #666; margin-bottom: 5px;">Total Transaksi Sukses</h4>
            <h2 style="color: #2563eb; margin: 0;">{{ $overallSummary->total_transactions ?? 0 }}</h2>
        </div>

        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="color: #666; margin-bottom: 5px;">Akumulasi Tiket Terjual</h4>
            <h2 style="color: #0d9488; margin: 0;">{{ $totalTicketsSold ?? 0 }} <span style="font-size: 14px; font-weight: normal;">tiket</span></h2>
        </div>

        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h4 style="color: #666; margin-bottom: 5px;">Total Pendapatan (Gross)</h4>
            <h2 style="color: #16a34a; margin: 0;">Rp {{ number_format($overallSummary->gross_revenue ?? 0, 0, ',', '.') }}</h2>
        </div>
    </div>

    <!-- TABEL REKAPITULASI PER EVENT -->
    <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <h3 style="margin-bottom: 15px;">Daftar Performa Per Event</h3>
        
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 12px;">Nama Event</th>
                    <th style="padding: 12px;">Jadwal Mulai</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px;">Tiket Terjual</th>
                    <th style="padding: 12px;">Total Pendapatan</th>
                    <th style="padding: 12px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($eventsReport as $ev)
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px; font-weight: bold;">{{ $ev->event_name }}</td>
                    <td style="padding: 12px;">{{ $ev->start_time ? \Carbon\Carbon::parse($ev->start_time)->format('d M Y, H:i') : '-' }}</td>
                    <td style="padding: 12px;"><span style="padding: 4px 8px; background: #e2e8f0; border-radius: 4px; font-size: 12px;">{{ ucfirst($ev->status) }}</span></td>
                    <td style="padding: 12px; color: #0d9488; font-weight: bold;">{{ $ev->total_sold }}</td>
                    <td style="padding: 12px; font-weight: bold; color: #16a34a;">Rp {{ number_format($ev->event_revenue, 0, ',', '.') }}</td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('eo.reports.sales', $ev->id) }}" style="padding: 6px 12px; background: #2563eb; color: #fff; border-radius: 4px; text-decoration: none; font-size: 12px;">Detail Laporan</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 20px; text-align: center; color: #666;">Belum ada event yang tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
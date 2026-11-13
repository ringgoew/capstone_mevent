<?php

namespace App\Http\Controllers\EO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EoReportController extends Controller
{
    public function index()
{
    // Cek sesi login EO
    if (!session('eo_login') || !session('eo_user_id')) {
        return redirect()->route('eo.login')->with('error', 'Sesi Berakhir');
    }

    $eoId = session('eo_user_id');

    // 1. Ringkasan Keseluruhan Semua Event (Status 'paid')
    $overallSummary = DB::table('orders')
        ->join('events', 'orders.event_id', '=', 'events.id')
        ->where('events.user_id', $eoId)
        ->where('orders.status', 'paid')
        ->selectRaw('
            COUNT(orders.id) as total_transactions,
            COALESCE(SUM(orders.total_amount), 0) as gross_revenue,
            COALESCE(SUM(orders.grand_total), 0) as net_revenue
        ')
        ->first();

    // 2. Total Keseluruhan Tiket Terjual
    $totalTicketsSold = DB::table('ticket_types')
        ->join('events', 'ticket_types.event_id', '=', 'events.id')
        ->where('events.user_id', $eoId)
        ->sum('ticket_types.sold_quota');

    // 3. Daftar Performa Semua Event Milik EO
    $eventsReport = DB::table('events')
        ->leftJoin('ticket_types', 'events.id', '=', 'ticket_types.event_id')
        ->where('events.user_id', $eoId)
        ->select(
            'events.id',
            'events.title as event_name',
            'events.start_time',
            'events.status',
            DB::raw('COALESCE(SUM(ticket_types.sold_quota), 0) as total_sold'),
            DB::raw('COALESCE(SUM(ticket_types.sold_quota * ticket_types.price), 0) as event_revenue')
        )
        ->groupBy('events.id', 'events.title', 'events.start_time', 'events.status')
        ->orderBy('events.start_time', 'desc')
        ->get();

    return view('EO.Reports.index', compact('overallSummary', 'totalTicketsSold', 'eventsReport'));
}
    public function salesReport($eventId)
    {
        // Cek sesi login EO
        if (!session('eo_login') || !session('eo_user_id')) {
            return redirect()->route('eo.login')->with('error', 'Sesi Berakhir');
        }

        $eoId = session('eo_user_id');

        // Pastikan event benar-benar milik EO yang sedang login
        $event = DB::table('events')
            ->where('id', $eventId)
            ->where('user_id', $eoId)
            ->first();

        if (!$event) {
            abort(403, 'Akses ditolak atau event tidak ditemukan.');
        }

        // Ringkasan Penjualan (Hanya order yang statusnya 'paid')
        $salesSummary = DB::table('orders')
            ->where('event_id', $event->id)
            ->where('status', 'paid')
            ->selectRaw('
                COUNT(id) as total_transactions,
                COALESCE(SUM(total_amount), 0) as gross_revenue,
                COALESCE(SUM(platform_fee), 0) as total_platform_fee,
                COALESCE(SUM(grand_total), 0) as net_revenue
            ')
            ->first();

        // Performa Jenis Tiket
        $ticketPerformances = DB::table('ticket_types')
            ->where('event_id', $event->id)
            ->select(
                'name as ticket_name',
                'price',
                'quota',
                'reserved_quota',
                'sold_quota',
                DB::raw('(quota - sold_quota - reserved_quota) as available_quota'),
                DB::raw('(sold_quota * price) as total_revenue_by_type')
            )
            ->get();

        return view('EO.Reports.sales', compact('event', 'salesSummary', 'ticketPerformances'));
    }
}
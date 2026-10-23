<?php

namespace App\Http\Controllers\EO;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EODashboardController extends Controller
{
    public function dashboard()
    {
        // Cek login EO
        if (!session('eo_login') || !session('eo_user_id')) {
            return redirect()->route('eo.login');
        }

        // ID EO yang sedang login
        $eoId = session('eo_user_id');

        // Ambil semua event milik EO yang sedang login
        $events = DB::table('events')
            ->leftJoin('ticket_types', 'events.id', '=', 'ticket_types.event_id')
            ->where('events.user_id', $eoId)
            ->select(
                'events.id',
                'events.title as nama',
                'events.start_time as tanggal',
                'events.venue_name as lokasi',
                'events.status',
                'events.banner_image as foto',
                DB::raw('COALESCE(SUM(ticket_types.sold_quota), 0) as tiket_terjual'),
                DB::raw('COALESCE(SUM(ticket_types.sold_quota * ticket_types.price), 0) as pendapatan')
            )
            ->groupBy(
                'events.id',
                'events.title',
                'events.start_time',
                'events.venue_name',
                'events.status',
                'events.banner_image'
            )
            ->orderBy('events.start_time', 'desc')
            ->get();

        // Statistik event milik EO
        $totalEvent = DB::table('events')
            ->where('user_id', $eoId)
            ->count();

        // Statistik tiket terjual dan pendapatan
        $totalTiketTerjual = DB::table('ticket_types')
            ->join('events', 'ticket_types.event_id', '=', 'events.id')
            ->where('events.user_id', $eoId)
            ->sum('ticket_types.sold_quota');

        $totalPendapatan = DB::table('ticket_types')
            ->join('events', 'ticket_types.event_id', '=', 'events.id')
            ->where('events.user_id', $eoId)
            ->selectRaw('COALESCE(SUM(ticket_types.sold_quota * ticket_types.price), 0) as total')
            ->value('total');

        // Data kehadiran belum dihitung karena perlu tabel kehadiran/check-in.
        $totalPesertaHadir = 0;

        $stats = [
            'total_event' => $totalEvent,
            'tiket_terjual' => $totalTiketTerjual,
            'pendapatan' => $totalPendapatan,
            'peserta_hadir' => $totalPesertaHadir,
        ];

        return view('eo.dashboard', compact('stats', 'events'));
    }
}

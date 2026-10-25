<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AdminEventModeration;
use App\Models\OrganizerProfile;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        if (!session('admin_login')) {
            return redirect()
                ->route('admin.login')
                ->withErrors([
                    'email' => 'Silakan login terlebih dahulu.',
                ]);
        }

        // Mengambil data riil dari database menggantikan data dummy
        $totalEvent = DB::table('events')->count();
        $eventPending = DB::table('events')->where('status', 'pending')->count();
        $totalEo = DB::table('organizer_profiles')->count();

        // Contoh perhitungan total tiket terjual atau total transaksi dari tabel orders/tickets
        // Menyesuaikan dengan struktur database Anda (misal berdasarkan tabel orders / tickets)
        $totalTiket = DB::table('orders')->count(); // atau sum dari jumlah kuantitas tiket jika ada

        $data = [
            'total_event' => $totalEvent,
            'event_pending' => $eventPending,
            'total_eo' => $totalEo,
            'total_tiket' => $totalTiket,
        ];

        return view(
            'admin.dashboard',
            compact('data')
        );
    }
}

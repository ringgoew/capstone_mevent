<?php

namespace App\Http\Controllers\EO;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class EODashboardController extends Controller
{
    // Dashboard EO (statistik masih dummy)
    public function dashboard()
    {
        if (!session('eo_login')) {
            return redirect()->route('eo.login');
        }

        $stats = [
            'total_event' => 2,
            'tiket_terjual' => 125,
            'pendapatan' => 6250000,
            'peserta_hadir' => 80,
        ];

        $events = [
            (object) [
                'nama' => 'E-Fest',
                'tanggal' => '15 Juni 2027',
                'lokasi' => 'Jubung',
                'tiket_terjual' => 80,
                'status' => 'Published',
                'foto' => 'efest.jpg' 
            ],
            (object) [
                'nama' => 'Civil Day',
                'tanggal' => '15 Juni 2027',
                'lokasi' => 'Tanjung Bunga',
                'tiket_terjual' => 45,
                'status' => 'Published',
                'foto' => 'civilday.jpg' 
            ],
        ];

        return view('eo.dashboard', compact('stats', 'events'));
    }
}
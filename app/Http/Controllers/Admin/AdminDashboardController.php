<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
        public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | CEK SESSION
        |--------------------------------------------------------------------------
        */

        if (!session('admin_login')) {
            return redirect()
                ->route('admin.login')
                ->withErrors([
                    'email' => 'Silakan login terlebih dahulu.',
                ]);
        }

        // data dummy untuk ditampilkan di dashboard
        $data = [
            'total_event' => 12,
            'event_pending' => 3,
            'total_eo' => 8,
            'total_tiket' => 245,
        ];


        return view(
            'admin.dashboard',
            compact('data')
        );
    }

}

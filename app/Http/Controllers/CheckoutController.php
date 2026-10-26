<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Menampilkan halaman checkout tiket
    public function show($slug)
    {
        $event = DB::table('events')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$event) {
            abort(404, 'Event tidak ditemukan.');
        }

        $tickets = DB::table('ticket_types')
            ->where('event_id', $event->id)
            ->orderBy('price', 'asc')
            ->get();

        return view('checkout_tiket', compact(
            'event',
            'tickets'
        ));
    }
}

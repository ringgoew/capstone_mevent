<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('events')
            ->where('status', 'published');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'ilike', "%{$keyword}%")
                    ->orWhere('venue_name', 'ilike', "%{$keyword}%")
                    ->orWhere('city', 'ilike', "%{$keyword}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $events = $query
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($event) {
                $event->harga = DB::table('ticket_types')
                    ->where('event_id', $event->id)
                    ->min('price');

                $event->nama = $event->title;
                $event->tanggal = $event->start_time;
                $event->location = $event->venue_name;
                $event->foto = $event->banner_image;

                return $event;
            });

        // Ambil daftar kategori
        $event_categories = DB::table('event_categories')
            ->orderBy('name')
            ->get();

        // Kirim data ke halaman home
        return view('home', compact('events', 'event_categories'));
    }
}
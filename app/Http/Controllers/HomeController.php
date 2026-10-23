<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil event yang sudah dipublikasikan
        $query = DB::table('events')
            ->where('status', 'published');

        // Pencarian berdasarkan nama event, lokasi, atau kota
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'ilike', "%{$keyword}%")
                    ->orWhere('venue_name', 'ilike', "%{$keyword}%")
                    ->orWhere('city', 'ilike', "%{$keyword}%");
            });
        }

        // Filter berdasarkan kategori dari tabel event_categories
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Ambil data event dari database
        $events = $query
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($event) {
                // Ambil harga tiket termurah
                $event->harga = DB::table('ticket_types')
                    ->where('event_id', $event->id)
                    ->min('price');

                // Sesuaikan nama properti dengan Blade
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
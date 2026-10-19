<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EventController extends Controller
{
    /**
     * Menampilkan form pembuatan event.
     */
    public function create()
    {
        if (!session('eo_login') || !session('eo_user_id')) {
            return redirect()->route('eo.login')
                ->with('error', 'Silakan login sebagai EO terlebih dahulu.');
        }

        // Mengambil kategori dari tabel PostgreSQL.
        $categories = DB::table('categories')
            ->orderBy('name')
            ->get();

        return view('eo.events.create', compact('categories'));
    }

    /**
     * Menyimpan event dan jenis tiket sebagai draft.
     */
    public function store(Request $request)
    {
        if (!session('eo_login') || !session('eo_user_id')) {
            return redirect()->route('eo.login')
                ->with('error', 'Silakan login sebagai EO terlebih dahulu.');
        }

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'banner_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'venue_name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'address' => 'required|string',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',

            'tickets' => 'required|array|min:1',
            'tickets.*.name' => 'required|string|max:255',
            'tickets.*.price' => 'required|numeric|min:0',
            'tickets.*.quota' => 'required|integer|min:1',
            'tickets.*.max_per_order' => 'required|integer|min:1',
            'tickets.*.start_sales' => 'nullable|date',
            'tickets.*.end_sales' => 'nullable|date',
        ]);

        $userId = session('eo_user_id');

        // Pastikan akun EO ada dan aktif.
        $organizer = DB::table('users')
            ->where('id', $userId)
            ->where('role', 'organizer')
            ->where('status', 'active')
            ->first();

        if (!$organizer) {
            return back()
                ->withInput()
                ->withErrors([
                    'event' => 'Akun EO tidak ditemukan atau tidak aktif di database.',
                ]);
        }

        // Validasi periode penjualan tiket.
        foreach ($validated['tickets'] as $index => $ticket) {
            if (
                !empty($ticket['start_sales']) &&
                !empty($ticket['end_sales']) &&
                strtotime($ticket['end_sales']) <= strtotime($ticket['start_sales'])
            ) {
                throw ValidationException::withMessages([
                    "tickets.$index.end_sales" =>
                        'Akhir penjualan harus setelah awal penjualan.',
                ]);
            }
        }

        $bannerPath = null;

        try {
            // Simpan gambar ke storage/app/public/events.
            $bannerPath = $request->file('banner_image')
                ->store('events', 'public');

            DB::transaction(function () use (
                $validated,
                $userId,
                $bannerPath
            ) {
                // Membuat slug unik untuk event.
                $baseSlug = Str::slug($validated['title']);

                if ($baseSlug === '') {
                    $baseSlug = 'event';
                }

                $slug = $baseSlug;
                $counter = 1;

                while (DB::table('events')->where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                // Menyimpan data event.
                $eventId = DB::table('events')->insertGetId([
                    'user_id' => $userId,
                    'category_id' => $validated['category_id'],
                    'title' => $validated['title'],
                    'slug' => $slug,
                    'description' => $validated['description'],
                    'banner_image' => $bannerPath,
                    'venue_name' => $validated['venue_name'],
                    'city' => $validated['city'],
                    'address' => $validated['address'],
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'status' => 'draft',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Menyimpan jenis tiket untuk event.
                foreach ($validated['tickets'] as $ticket) {
                    DB::table('ticket_types')->insert([
                        'event_id' => $eventId,
                        'name' => $ticket['name'],
                        'price' => $ticket['price'],
                        'quota' => $ticket['quota'],
                        'reserved_quota' => 0,
                        'sold_quota' => 0,
                        'max_per_order' => $ticket['max_per_order'],
                        'start_sales' => $ticket['start_sales'] ?? null,
                        'end_sales' => $ticket['end_sales'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
        } catch (Throwable $e) {
            // Hapus gambar jika proses penyimpanan gagal.
            if ($bannerPath) {
                Storage::disk('public')->delete($bannerPath);
            }

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'event' => 'Event gagal disimpan. Periksa data dan konfigurasi database.',
                ]);
        }

        return redirect()->route('eo.events.index')
            ->with('success', 'Draf event dan jenis tiket berhasil disimpan!');
    }

    /**
     * Menampilkan daftar event milik EO.
     */
    public function index()
    {
        if (!session('eo_login') || !session('eo_user_id')) {
            return redirect()->route('eo.login');
        }

        $events = DB::table('events')
            ->where('user_id', session('eo_user_id'))
            ->orderByDesc('created_at')
            ->get();

        return view('eo.events.index', compact('events'));
    }

    /**
     * Mengajukan event draft untuk diperiksa admin.
     */
    public function submitForReview($id)
    {
        if (!session('eo_login') || !session('eo_user_id')) {
            return redirect()->route('eo.login');
        }

        $event = DB::table('events')
            ->where('id', $id)
            ->where('user_id', session('eo_user_id'))
            ->first();

        if (!$event) {
            return redirect()->route('eo.events.index')
                ->with('error', 'Event tidak ditemukan.');
        }

        if ($event->status !== 'draft') {
            return redirect()->route('eo.events.index')
                ->with('error', 'Hanya event berstatus draft yang dapat diajukan.');
        }

        $ticketCount = DB::table('ticket_types')
            ->where('event_id', $id)
            ->count();

        if ($ticketCount < 1) {
            return redirect()->route('eo.events.index')
                ->with('error', 'Tambahkan minimal satu jenis tiket.');
        }

        DB::table('events')
            ->where('id', $id)
            ->where('user_id', session('eo_user_id'))
            ->where('status', 'draft')
            ->update([
                'status' => 'pending',
                'updated_at' => now(),
            ]);

        return redirect()->route('eo.events.index')
            ->with('success', 'Event berhasil diajukan untuk verifikasi admin.');
    }
}
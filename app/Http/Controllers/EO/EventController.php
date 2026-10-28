<?php

namespace App\Http\Controllers\EO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EventController extends Controller
{
    // Form tambah event
    public function create()
    {
        if (!session('eo_login') || !session('eo_user_id')) {
            return redirect()->route('eo.login')
                ->with('error', 'Silakan login sebagai EO terlebih dahulu.');
        }

        $categories = DB::table('event_categories')
            ->orderBy('name')
            ->get();

        $oldTickets = old('tickets', []);
        return view('eo.events.create', compact('categories', 'oldTickets'));
    }

    // Simpan event dan jenis tiket
    public function store(Request $request)
    {
        if (!session('eo_login') || !session('eo_user_id')) {
            return redirect()->route('eo.login')
                ->with('error', 'Silakan login sebagai EO terlebih dahulu.');
        }

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:event_categories,id',
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

        // Pastikan total kuota seluruh jenis tiket tidak melebihi batas kapasitas event.
        $this->validateTicketQuota($validated['tickets']);

        $userId = session('eo_user_id');

        $organizer = DB::table('users')
            ->where('id', $userId)
            ->where('role', 'organizer')
            ->where('status', 'verified')
            ->first();

        if (!$organizer) {
            return back()
                ->withInput()
                ->withErrors([
                    'event' => 'Akun EO tidak ditemukan atau tidak aktif di database.',
                ]);
        }

        // Validasi waktu penjualan tiket
        foreach ($validated['tickets'] as $index => $ticket) {
            if (
                !empty($ticket['start_sales']) &&
                !empty($ticket['end_sales']) &&
                strtotime($ticket['end_sales']) <= strtotime($ticket['start_sales'])
            ) {
                throw ValidationException::withMessages([
                    "tickets.$index.end_sales" => 'Akhir penjualan harus setelah awal penjualan.',
                ]);
            }
        }
        $forbiddenKeywords = ['judi', 'sara', 'kekerasan', 'narkoba', 'pornografi', 'radikalisme', 'sabung ayam', 'tarung bebas', 'ponzi', 'jancok', 'patek'];
        $textToScan = strtolower($validated['title'] . ' ' . $validated['description']);

        $detectedKeywords = [];
        foreach ($forbiddenKeywords as $word) {
            if (str_contains($textToScan, $word)) {
                $detectedKeywords[] = $word;
            }
        }

        // Jika mengandung kata terlarang -> simpan sebagai 'draft' (Need Fix)
        // Jika lolos filter -> simpan sebagai 'pending' (Pending Review)
        $hasForbiddenContent = count($detectedKeywords) > 0;
        $initialStatus = $hasForbiddenContent ? 'draft' : 'pending';

        $bannerPath = null;

        try {
            $bannerPath = $request->file('banner_image')->store('events', 'public');

            DB::transaction(function () use (
                $validated,
                $userId,
                $bannerPath,
                $initialStatus
            ) {
                // Buat slug unik
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

                // Simpan event dengan status 'pending' (atau 'draft' jika terdeteksi SARA)
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
                    'status' => $initialStatus,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Simpan jenis tiket
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

        // Umpan balik jika mengandung kata terlarang
        if ($hasForbiddenContent) {
            return redirect()->route('eo.events.index')
                ->with('error', 'Event disimpan sebagai Draf (Perlu Perbaikan) karena terdeteksi kata terlarang: (' . implode(', ', $detectedKeywords) . '). Silakan perbaiki sebelum mengajukan kembali.');
        }

        return redirect()->route('eo.events.index')
            ->with('success', 'Event berhasil dibuat dan diajukan untuk verifikasi admin!');
    }

    // Daftar event milik EO
    public function index()
    {
        if (!session('eo_login') || !session('eo_user_id')) {
            return redirect()->route('eo.login');
        }

        $events = DB::table('events')
            ->where('user_id', session('eo_user_id'))
            ->orderByDesc('created_at')
            ->get();

        return view('eo.events.daftar', compact('events'));
    }

    // Ajukan event untuk ditinjau admin
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


    public function edit($id)
    {
        if (!session('eo_login') || !session('eo_user_id')) {
            return redirect()->route('eo.login');
        }

        $userId = session('eo_user_id');

        $event = DB::table('events')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$event) {
            return redirect()->route('eo.events.index')->with('error', 'Event tidak ditemukan.');
        }

        if (!in_array($event->status, ['draft', 'rejected'])) {
            return redirect()->route('eo.events.index')->with('error', 'Event yang sedang diajukan atau dipublikasikan tidak dapat diedit.');
        }

        $categories = DB::table('event_categories')->get();
        $tickets = DB::table('ticket_types')->where('event_id', $id)->get();

        return view('eo.events.edit', compact('event', 'categories', 'tickets'));
    }

    public function update(Request $request, $id)
    {
        if (!session('eo_login') || !session('eo_user_id')) {
            return redirect()->route('eo.login');
        }

        $userId = session('eo_user_id');

        $event = DB::table('events')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$event || !in_array($event->status, ['draft', 'rejected'])) {
            return redirect()->route('eo.events.index')->with('error', 'Event tidak dapat diperbarui.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:event_categories,id',
            'description' => 'required|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'venue_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'tickets' => 'required|array|min:1',
            'tickets.*.name' => 'required|string|max:255',
            'tickets.*.price' => 'required|numeric|min:0',
            'tickets.*.quota' => 'required|integer|min:1',
            'tickets.*.max_per_order' => 'required|integer|min:1',
        ]);

        // Validasi juga saat EO mengedit kuota tiket event.
        $this->validateTicketQuota($validated['tickets']);

        $bannerPath = $event->banner_image;
        if ($request->hasFile('banner_image')) {
            if ($bannerPath && Storage::disk('public')->exists($bannerPath)) {
                Storage::disk('public')->delete($bannerPath);
            }
            $bannerPath = $request->file('banner_image')->store('events', 'public');
        }

        // Jika sebelumnya rejected, kembalikan ke draf saat diperbaiki
        $newStatus = ($event->status === 'rejected') ? 'draft' : $event->status;

        DB::transaction(function () use ($request, $validated, $id, $bannerPath, $newStatus) {
            DB::table('events')->where('id', $id)->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . time(),
                'category_id' => $request->category_id,
                'description' => $request->description,
                'banner_image' => $bannerPath,
                'venue_name' => $request->venue_name,
                'city' => $request->city,
                'address' => $request->address,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => $newStatus,
                'rejection_reason' => null, // Reset alasan penolakan
                'updated_at' => now(),
            ]);

            // Hapus tiket lama dan masukkan tiket baru yang ter-update
            DB::table('ticket_types')->where('event_id', $id)->delete();

            foreach ($validated['tickets'] as $ticket) {
                DB::table('ticket_types')->insert([
                    'event_id' => $id,
                    'name' => $ticket['name'],
                    'price' => $ticket['price'],
                    'quota' => $ticket['quota'],
                    'max_per_order' => $ticket['max_per_order'],
                    'start_sales' => $ticket['start_sales'] ?? null,
                    'end_sales' => $ticket['end_sales'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return redirect()->route('eo.events.index')->with('success', 'Event berhasil diperbarui!');
    }

    // Detail event untuk halaman publik
    public function show($slug)
    {
        $event = DB::table('events')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$event) {
            abort(404);
        }

        $category = DB::table('event_categories')
            ->where('id', $event->category_id)
            ->first();

        $tickets = DB::table('ticket_types')
            ->where('event_id', $event->id)
            ->orderBy('price', 'asc')
            ->get();

        return view('detail', compact(
            'event',
            'category',
            'tickets'
        ));
    }

    public function eoShow($id)
    {
        if (!session('eo_login') || !session('eo_user_id')) {
            return redirect()->route('eo.login');
        }

        $userId = session('eo_user_id');

        $event = DB::table('events')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$event) {
            return redirect()->route('eo.events.index')
                ->with('error', 'Event tidak ditemukan.');
        }

        $category = DB::table('event_categories')
            ->where('id', $event->category_id)
            ->first();

        $tickets = DB::table('ticket_types')
            ->where('event_id', $id)
            ->orderBy('price', 'asc')
            ->get();

        return view('eo.events.detail', compact('event', 'category', 'tickets'));
    }

    private function validateTicketQuota(array $tickets): void
    {
        $totalQuotaInput = array_sum(array_column($tickets, 'quota'));

        // Atur melalui config/events.php; 10000 digunakan jika konfigurasi belum tersedia.
        $maxVenueCapacity = (int) config('events.max_venue_capacity', 10000);

        if ($totalQuotaInput > $maxVenueCapacity) {
            throw ValidationException::withMessages([
                'tickets' => [
                    "Total kuota tiket ({$totalQuotaInput}) melebihi kapasitas maksimum event ({$maxVenueCapacity}).",
                ],
            ]);
        }
    }
}

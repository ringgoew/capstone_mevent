<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    // Menampilkan halaman checkout
    public function show($slug)
    {
        $event = DB::table('events')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$event) {
            abort(404, 'Event tidak ditemukan.');
        }

        $e_tickets = DB::table('ticket_types')
            ->where('event_id', $event->id)
            ->orderBy('price', 'asc')
            ->get();

        return view('checkout_tiket', compact('event', 'e_tickets'));
    }

    // Memproses checkout dan membuat transaksi Midtrans
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|integer',
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email|max:255',
            'buyer_phone' => 'required|string|max:20',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:0',
        ]);

        try {
            $result = DB::transaction(function () use ($validated) {

                $event = DB::table('events')
                    ->where('id', $validated['event_id'])
                    ->where('status', 'published')
                    ->first();

                if (!$event) {
                    throw new \Exception('Event tidak ditemukan.');
                }

                // Ambil tiket yang jumlahnya lebih dari nol
                $quantities = collect($validated['quantities'])
                    ->filter(fn($qty) => (int) $qty > 0);

                if ($quantities->isEmpty()) {
                    throw new \Exception('Pilih minimal satu tiket.');
                }

                $e_tickets = DB::table('ticket_types')
                    ->where('event_id', $event->id)
                    ->whereIn('id', $quantities->keys())
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                if ($e_tickets->count() != $quantities->count()) {
                    throw new \Exception('Jenis tiket tidak valid.');
                }

                $total = 0;
                $items = [];

                foreach ($quantities as $ticketId => $quantity) {
                    $ticket = $e_tickets[$ticketId];
                    $quantity = (int) $quantity;

                    $available = $ticket->quota
                        - ($ticket->reserved_quota ?? 0)
                        - ($ticket->sold_quota ?? 0);

                    if ($quantity > $available) {
                        throw new \Exception('Stok tiket ' . $ticket->name . ' tidak mencukupi.');
                    }

                    if (!empty($ticket->max_per_order) && $quantity > $ticket->max_per_order) {
                        throw new \Exception('Jumlah tiket ' . $ticket->name . ' melebihi batas pembelian.');
                    }

                    $subtotal = $ticket->price * $quantity;
                    $total += $subtotal;

                    $items[] = [
                        'ticket' => $ticket,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ];

                    // REKOMENDASI: Masukkan ke reserved_quota dulu selama status pending
                    DB::table('ticket_types')
                        ->where('id', $ticketId)
                        ->update([
                            'reserved_quota' => ($ticket->reserved_quota ?? 0) + $quantity
                        ]);
                }

                // 1. Membuat order
                $orderCode = 'ORD-' . strtoupper(Str::random(12));
                $orderId = DB::table('orders')->insertGetId([
                    'order_code' => $orderCode,
                    'user_id' => null,
                    'customer_name' => $validated['buyer_name'],
                    'customer_email' => $validated['buyer_email'],
                    'customer_phone' => $validated['buyer_phone'],
                    'event_id' => $event->id,
                    'total_amount' => $total,
                    'platform_fee' => 0,
                    'grand_total' => $total,
                    'status' => 'pending',
                    'expires_at' => now()->addHours(24),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 2. Menyimpan detail order_items Sekaligus GENERATE E-TIKET & QR CODE
foreach ($items as $item) {
    $orderItemId = DB::table('order_items')->insertGetId([
        'order_id' => $orderId,
        'ticket_type_id' => $item['ticket']->id,
        'quantity' => $item['quantity'],
        'price' => $item['ticket']->price,
        'subtotal' => $item['subtotal'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Generate e-tiket sejumlah quantity yang dibeli
    for ($i = 0; $i < $item['quantity']; $i++) {
        $ticketCode = 'TKT-' . strtoupper(Str::random(10));
        
        DB::table('e_tickets')->insert([
            'order_id'       => $orderId,
            'order_item_id'  => $orderItemId, // Wajib di e_tickets
            'ticket_code'    => $ticketCode,
            'qr_code_hash'   => md5($ticketCode), // Hash unik untuk QR code
            'attendee_name'  => $validated['buyer_name'], // Nama pemesan/pengunjung
            'attendee_email' => $validated['buyer_email'], // Email pemesan
            'attendee_phone' => $validated['buyer_phone'], // No HP pemesan
            'status'         => 'valid',    // Default status di e_tickets adalah 'valid'
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}

                return DB::table('orders')->where('id', $orderId)->first();
            });

            // Konfigurasi & Ambil Snap Token Midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $result->order_code,
                    'gross_amount' => (int) round($result->grand_total),
                ],
                'customer_details' => [
                    'first_name' => $result->customer_name,
                    'email' => $result->customer_email,
                    'phone' => $result->customer_phone,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            DB::table('orders')
                ->where('id', $result->id)
                ->update(['snap_token' => $snapToken, 'updated_at' => now()]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_code' => $result->order_code,
            ]);

        } catch (\Throwable $e) {

            Log::error('Checkout gagal: ' . $e->getMessage());

            return response()->json([
                'message' => $e instanceof \Illuminate\Validation\ValidationException
                    ? 'Data checkout tidak valid.'
                    : $e->getMessage(),
            ], 422);
        }
    }
}
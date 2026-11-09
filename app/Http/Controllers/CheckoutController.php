<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $tickets = DB::table('ticket_types')
            ->where('event_id', $event->id)
            ->orderBy('price', 'asc')
            ->get();

        return view('checkout_tiket', compact('event', 'tickets'));
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

                $tickets = DB::table('ticket_types')
                    ->where('event_id', $event->id)
                    ->whereIn('id', $quantities->keys())
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                if ($tickets->count() != $quantities->count()) {
                    throw new \Exception('Jenis tiket tidak valid.');
                }

                $total = 0;
                $items = [];

                foreach ($quantities as $ticketId => $quantity) {

                    $ticket = $tickets[$ticketId];
                    $quantity = (int) $quantity;

                    $available = $ticket->quota
                        - ($ticket->reserved_quota ?? 0)
                        - ($ticket->sold_quota ?? 0);

                    if ($quantity > $available) {
                        throw new \Exception(
                            'Stok tiket ' . $ticket->name . ' tidak mencukupi.'
                        );
                    }

                    if (
                        !empty($ticket->max_per_order) &&
                        $quantity > $ticket->max_per_order
                    ) {
                        throw new \Exception(
                            'Jumlah tiket ' . $ticket->name . ' melebihi batas pembelian.'
                        );
                    }

                    $subtotal = $ticket->price * $quantity;
                    $total += $subtotal;

                    $items[] = [
                        'ticket' => $ticket,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ];

                    // Reservasi stok sementara
                    DB::table('ticket_types')
                        ->where('id', $ticketId)
                        ->update([
                            'reserved_quota' =>
                                ($ticket->reserved_quota ?? 0) + $quantity
                        ]);
                }

                // Membuat order
                $orderId = DB::table('orders')->insertGetId([
                    'order_code' => 'ORD-' . strtoupper(Str::random(12)),
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

                // Menyimpan detail tiket pesanan
                foreach ($items as $item) {
                    DB::table('order_items')->insert([
                        'order_id' => $orderId,
                        'ticket_type_id' => $item['ticket']->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['ticket']->price,
                        'subtotal' => $item['subtotal'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                return DB::table('orders')
                    ->where('id', $orderId)
                    ->first();
            });

            // Konfigurasi Midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Membuat transaksi pembayaran
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
                ->update([
                    'snap_token' => $snapToken,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_code' => $result->order_code,
            ]);

        } catch (\Throwable $e) {

            \Log::error('Checkout gagal: ' . $e->getMessage());

            return response()->json([
                'message' => $e instanceof \Illuminate\Validation\ValidationException
                    ? 'Data checkout tidak valid.'
                    : $e->getMessage(),
            ], 422);
        }
    }
}

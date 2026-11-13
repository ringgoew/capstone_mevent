
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    // MEMBUAT TRANSAKSI PEMBAYARAN
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id'    => 'required|integer',
            'buyer_name'  => 'required|string|max:255',
            'buyer_email' => 'required|email|max:255',
            'buyer_phone' => 'required|string|max:20',
            'quantities'  => 'required|array',
            'quantities.*' => 'required|integer|min:0',
        ]);

        try {
            return DB::transaction(function () use ($validated) {

                $event = DB::table('events')
                    ->where('id', $validated['event_id'])
                    ->where('status', 'published')
                    ->first();

                if (!$event) {
                    throw new \Exception('Event tidak ditemukan.');
                }

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

                    $subtotal = $ticket->price * $quantity;
                    $total += $subtotal;

                    $items[] = [
                        'ticket' => $ticket,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ];

                    DB::table('ticket_types')
                        ->where('id', $ticketId)
                        ->increment('reserved_quota', $quantity);
                }

                // MEMBUAT ORDER
                $orderCode = 'MIVENT-' . strtoupper(Str::random(12));

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

                $midtransItems = [];

                // MEMBUAT ORDER ITEMS
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

                    $midtransItems[] = [
                        'id' => (string) $item['ticket']->id,
                        'price' => (int) $item['ticket']->price,
                        'quantity' => $item['quantity'],
                        'name' => $item['ticket']->name,
                    ];
                }

                // MEMBUAT DATA PAYMENT
                DB::table('payments')->insert([
                    'order_id' => $orderId,
                    'payment_gateway' => 'midtrans',
                    'payment_method' => 'bank_transfer',
                    'amount' => $total,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // KONFIGURASI MIDTRANS
                Config::$serverKey = config('midtrans.server_key');
                Config::$isProduction = config('midtrans.is_production');
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderCode,
                        'gross_amount' => (int) $total,
                    ],
                    'item_details' => $midtransItems,
                    'customer_details' => [
                        'first_name' => $validated['buyer_name'],
                        'email' => $validated['buyer_email'],
                        'phone' => $validated['buyer_phone'],
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);

                DB::table('orders')
                    ->where('id', $orderId)
                    ->update([
                        'snap_token' => $snapToken,
                        'updated_at' => now(),
                    ]);

                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'order_code' => $orderCode,
                ]);
            });

        } catch (\Throwable $e) {

            Log::error('Checkout gagal: ' . $e->getMessage());

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    // CALLBACK MIDTRANS
    public function callback(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notif = new Notification();

            $transactionStatus = $notif->transaction_status;
            $orderCode = $notif->order_id;

            $order = DB::table('orders')
                ->where('order_code', $orderCode)
                ->first();

            if (!$order) {
                return response()->json([
                    'message' => 'Order tidak ditemukan.'
                ], 404);
            }

            // PEMBAYARAN BERHASIL
            if (
                $transactionStatus == 'settlement' ||
                (
                    $transactionStatus == 'capture' &&
                    $notif->fraud_status == 'accept'
                )
            ) {

                DB::transaction(function () use ($order) {

                    $lockedOrder = DB::table('orders')
                        ->where('id', $order->id)
                        ->lockForUpdate()
                        ->first();

                    // UPDATE STATUS DAN STOK SEKALI SAJA
                    if ($lockedOrder->status != 'paid') {

                        DB::table('orders')
                            ->where('id', $order->id)
                            ->update([
                                'status' => 'paid',
                                'paid_at' => now(),
                                'updated_at' => now(),
                            ]);

                        DB::table('payments')
                            ->where('order_id', $order->id)
                            ->update([
                                'status' => 'success',
                                'paid_at' => now(),
                                'updated_at' => now(),
                            ]);

                        $items = DB::table('order_items')
                            ->where('order_id', $order->id)
                            ->get();

                        foreach ($items as $item) {

                            DB::table('ticket_types')
                                ->where('id', $item->ticket_type_id)
                                ->decrement('reserved_quota', $item->quantity);

                            DB::table('ticket_types')
                                ->where('id', $item->ticket_type_id)
                                ->increment('sold_quota', $item->quantity);
                        }
                    }

                    // GENERATE E-TICKET
                    $items = DB::table('order_items')
                        ->where('order_id', $order->id)
                        ->get();

                    foreach ($items as $item) {

                        $existing = DB::table('e_tickets')
                            ->where('order_item_id', $item->id)
                            ->count();

                        if ($existing > 0) {
                            continue;
                        }

                        for ($i = 0; $i < $item->quantity; $i++) {

                            $ticketCode = 'TKT-' .
                                strtoupper(Str::random(12));

                            DB::table('e_tickets')->insert([
                                'order_id' => $order->id,
                                'order_item_id' => $item->id,
                                'ticket_code' => $ticketCode,
                                'qr_code_hash' => hash(
                                    'sha256',
                                    $ticketCode
                                ),
                                'attendee_name' => $order->customer_name,
                                'attendee_email' => $order->customer_email,
                                'attendee_phone' => $order->customer_phone,
                                'status' => 'valid',
                                'checked_in_at' => null,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                });

            }

            // PEMBAYARAN GAGAL / KADALUWARSA
            elseif (in_array($transactionStatus, [
                'expire', 'deny', 'cancel'
            ])) {

                DB::transaction(function () use ($order) {

                    $lockedOrder = DB::table('orders')
                        ->where('id', $order->id)
                        ->lockForUpdate()
                        ->first();

                    if ($lockedOrder->status != 'pending') {
                        return;
                    }

                    DB::table('orders')
                        ->where('id', $order->id)
                        ->update([
                            'status' => 'cancelled',
                            'updated_at' => now(),
                        ]);

                    DB::table('payments')
                        ->where('order_id', $order->id)
                        ->update([
                            'status' => 'failed',
                            'updated_at' => now(),
                        ]);

                    $items = DB::table('order_items')
                        ->where('order_id', $order->id)
                        ->get();

                    foreach ($items as $item) {
                        DB::table('ticket_types')
                            ->where('id', $item->ticket_type_id)
                            ->decrement('reserved_quota', $item->quantity);
                    }
                });
            }

            return response()->json([
                'message' => 'Callback berhasil diproses.'
            ]);

        } catch (\Throwable $e) {

            Log::error('Midtrans callback gagal: ' . $e->getMessage());

            return response()->json([
                'message' => 'Gagal memproses callback.'
            ], 500);
        }
    }
}
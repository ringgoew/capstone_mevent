<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'event_id'    => 'required|integer',
            'buyer_name'  => 'required|string|max:255',
            'buyer_email' => 'required|email|max:255',
            'buyer_phone' => 'required|string|max:20',
            'quantities'  => 'required|array',
        ]);

        $totalPrice = 0;
        $orderItemsData = [];

        // Gunakan DB Transaction agar jika ada kesalahan, stok/order tidak rusak
        return DB::transaction(function () use ($request, &$totalPrice, &$orderItemsData) {

            foreach ($request->quantities as $ticketId => $qty) {
                $qty = (int) $qty;
                if ($qty > 0) {
                    $ticket = DB::table('ticket_types')->where('id', $ticketId)->lockForUpdate()->first();

                    if (!$ticket) {
                        return response()->json(['message' => 'Tipe tiket tidak ditemukan.'], 404);
                    }

                    // Cek ketersediaan stok aktual
                    $available = $ticket->quota - ($ticket->reserved_quota ?? 0) - ($ticket->sold_quota ?? 0);
                    if ($qty > $available) {
                        return response()->json(['message' => "Stok tiket {$ticket->name} tidak mencukupi."], 422);
                    }

                    $subtotal = $ticket->price * $qty;
                    $totalPrice += $subtotal;

                    $orderItemsData[] = [
                        'ticket_type_id' => $ticket->id,
                        'name'           => substr($ticket->name, 0, 50),
                        'price'          => (int) $ticket->price,
                        'quantity'       => $qty,
                        'subtotal'       => $subtotal,
                    ];

                    // Reservasi stok tiket sementara
                    DB::table('ticket_types')
                        ->where('id', $ticket->id)
                        ->increment('reserved_quota', $qty);
                }
            }

            if ($totalPrice <= 0 || empty($orderItemsData)) {
                return response()->json(['message' => 'Pilih minimal satu tiket.'], 422);
            }

            $orderCode = 'MIVENT-' . time() . '-' . rand(100, 999);

            $orderId = DB::table('orders')->insertGetId([
                'order_code'     => $orderCode,
                'customer_name'  => $request->buyer_name,
                'customer_email' => $request->buyer_email,
                'customer_phone' => $request->buyer_phone,
                'event_id'       => $request->event_id,
                'total_amount'   => $totalPrice,
                'platform_fee'   => 0,
                'grand_total'    => $totalPrice,
                'status'         => 'pending',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 2. Simpan ke tabel order_items
            $midtransItems = [];
            foreach ($orderItemsData as $item) {
                DB::table('order_items')->insert([
                    'order_id'       => $orderId,
                    'ticket_type_id' => $item['ticket_type_id'],
                    'quantity'       => $item['quantity'],
                    'price'          => $item['price'],
                    'subtotal'       => $item['subtotal'],
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                $midtransItems[] = [
                    'id'       => (string) $item['ticket_type_id'],
                    'price'    => $item['price'],
                    'quantity' => $item['quantity'],
                    'name'     => $item['name'],
                ];
            }

            // 3. Simpan ke tabel payments
            DB::table('payments')->insert([
                'order_id'        => $orderId,
                'payment_gateway' => 'midtrans',
                'payment_method'  => 'bank_transfer',
                'amount'          => $totalPrice,
                'status'          => 'pending',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // Setup Midtrans Snap Token
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderCode,
                    'gross_amount' => $totalPrice,
                ],
                'item_details' => $midtransItems,
                'customer_details' => [
                    'first_name' => $request->buyer_name,
                    'email'      => $request->buyer_email,
                    'phone'      => $request->buyer_phone,
                ],
                'enabled_payments' => [
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'cimb_va',
                    'permata_va',
                    'other_va'
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            // Update snap_token di tabel orders
            DB::table('orders')->where('id', $orderId)->update(['snap_token' => $snapToken]);

            return response()->json([
                'snap_token' => $snapToken,
                'order_code' => $orderCode,
            ]);
        });
    }

    public function callback(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        $transactionStatus = $notif->transaction_status;
        $orderCode = $notif->order_id;

        $order = DB::table('orders')->where('order_code', $orderCode)->first();
        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            DB::transaction(function () use ($order) {
                // Update status Order & Payment
                DB::table('orders')->where('id', $order->id)->update([
                    'status'  => 'paid',
                    'paid_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('payments')->where('order_id', $order->id)->update([
                    'status'     => 'success',
                    'paid_at'    => now(),
                    'updated_at' => now(),
                ]);

                // Pindahkan kuota reserved ke sold_quota
                $items = DB::table('order_items')->where('order_id', $order->id)->get();
                foreach ($items as $item) {
                    DB::table('ticket_types')
                        ->where('id', $item->ticket_type_id)
                        ->decrement('reserved_quota', $item->quantity);

                    DB::table('ticket_types')
                        ->where('id', $item->ticket_type_id)
                        ->increment('sold_quota', $item->quantity);
                }
            });
        } else if (in_array($transactionStatus, ['expire', 'deny', 'cancel'])) {
            DB::transaction(function () use ($order) {
                DB::table('orders')->where('id', $order->id)->update(['status' => 'cancelled', 'updated_at' => now()]);
                DB::table('payments')->where('order_id', $order->id)->update(['status' => 'failed', 'updated_at' => now()]);

                // Kembalikan kuota reserved jika transaksi batal
                $items = DB::table('order_items')->where('order_id', $order->id)->get();
                foreach ($items as $item) {
                    DB::table('ticket_types')
                        ->where('id', $item->ticket_type_id)
                        ->decrement('reserved_quota', $item->quantity);
                }
            });
        }

        return response()->json(['message' => 'Callback diproses successfully']);
    }
}

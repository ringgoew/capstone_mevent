<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function checkout()
    {
        // 1. Setup konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // 2. Siapkan data pesanan (Contoh data statis)
        $params = array(
            'transaction_details' => array(
                'order_id' => rand(), // ID pesanan harus unik
                'gross_amount' => 50000, // Harga total
            ),
            'customer_details' => array(
                'first_name' => 'Budi',
                'last_name' => 'Pratama',
                'email' => 'budi.pra@example.com',
                'phone' => '08111222333',
            ),
        );

        // 3. Minta Token Snap dari Midtrans
        $snapToken = Snap::getSnapToken($params);

        // 4. Kirim token ke halaman view (frontend)
        return view('checkout', compact('snapToken'));
    }
    
    public function notification(Request $request)
    {
        // 1. Setup konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        // 2. Buat instance Notification dari Midtrans
        try {
            $notif = new \Midtrans\Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memproses notifikasi', 'error' => $e->getMessage()], 400);
        }

        $transactionStatus = $notif->transaction_status;
        $orderId = $notif->order_id;

        // 3. Tentukan logika pembaruan basis data (Database) berdasarkan status
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            // Pembayaran Berhasil / Lunas
            // Contoh: Order::where('id', $orderId)->update(['status' => 'lunas']);
            error_log("Pesanan $orderId berhasil dibayar.");
            
        } else if ($transactionStatus == 'pending') {
            // Menunggu Pembayaran
            error_log("Pesanan $orderId sedang menunggu pembayaran.");
            
        } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            // Pembayaran Gagal / Dibatalkan / Kedaluwarsa
            error_log("Pesanan $orderId gagal atau dibatalkan.");
        }

        // 4. Kembalikan respon HTTP 200 agar Midtrans tahu notifikasi sudah diterima
        return response()->json(['message' => 'Notifikasi berhasil diterima']);
    }
}


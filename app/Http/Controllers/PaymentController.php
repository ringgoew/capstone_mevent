<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
// use App\Models\Order;

class PaymentController extends Controller
{
    // public function checkout($id)
    // {
    //     // Setup konfigurasi Midtrans
    //     Config::$serverKey = config('midtrans.server_key');
    //     Config::$isProduction = config('midtrans.is_production');
    //     Config::$isSanitized = true;
    //     Config::$is3ds = true;

    //     // Panggil logika query dari Model Order
    //     $orderData = Order::getPaymentDetails($id);

    //     if (!$orderData) {
    //         abort(404, 'Data pesanan tidak ditemukan.');
    //     }

    //     // Siapkan parameter Midtrans
    //     $params = array(
    //         'transaction_details' => array(
    //             // Menggunakan order_code asli dari tabel Orders
    //             'order_id' => $orderData->order_code,
    //             // Menggunakan grand_total dari tabel Orders
    //             'gross_amount' => (int) $orderData->grand_total,
    //         ),
    //         'customer_details' => array(
    //             'first_name' => $orderData->customer_name,
    //             'email'      => $orderData->customer_email,
    //             'phone'      => $orderData->customer_phone ?? '0800000000',
    //         ),
    //     );

    //     // Minta Token Snap
    //     $snapToken = Snap::getSnapToken($params);

    //     // Lempar token dan data gabungan ke view
    //     return view('payment.checkout', compact('snapToken', 'orderData'));
    // }


    public function checkout()
    {
        // Setup konfigurasi midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Siapkan data pesanan (contoh data statis)
        $params = array(

            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => 500000,
            ),
            'customer_details' => array(
                'first_name' => 'Budi',
                'last_name' => 'Pratama',
                'email' => 'budi@example.com',
                'phone' => '08123456789',
            ),
        );

        // dd([
        //     'Server_Key_Yang_Terbaca' => \Midtrans\Config::$serverKey,
        //     'Status_Produksi' => \Midtrans\Config::$isProduction,
        // ]);

        // Minta Token Snap Dari Midtrans
        $snapToken = Snap::getSnapToken($params);

        // Kirim Token Ke Halaman View
        return view('payment.checkout', compact('snapToken'));
    }

    public function notification(Request $request)
    {
        // Setup konfigurasi midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        // buat instance notification dari midtrans
        try {
            $notif = new \Midtrans\Notification();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal Memproses Notifikasi', 'error_message' => $e->getMessage()], 400);
        }

        $transactionStatus = $notif->transaction_status;
        $orderId = $notif->order_id;

        //tentukan logika pembaruan basis data (database) berdasarkan status
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            // Pembayaran berhasil
            // Update status pembayaran di database
            // Contoh: Payment::where('order_id', $orderId)->update(['status' => 'lunas']);
            error_log("pesanan $orderId berhasil dibayar.");
        } elseif ($transactionStatus == 'pending') {
            // Menunggu pembayaran
            error_log("pesanan $orderId menunggu pembayaran.");
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            // Pembayaran ditolak
            // Update status pembayaran di database
            error_log("pesanan $orderId gagal atau dibatalkan.");
        }

        // kembalikan respons http 200 agar midtrans tau notifikasi sudah diterima
        return response()->json(['message' => 'Notifikasi Berhasil Diterima']);
    }

    public function finish(Request $request)
    {
        // Tangkap parameter yang dikirim Midtrans melalui URL
        $orderId = $request->query('order_id');
        $transactionStatus = $request->query('transaction_status');

        // Cek status transaksi
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            // Jika sukses dibayar
            return view('payment.approved', compact('orderId'));
        } elseif ($transactionStatus == 'pending') {
            // Jika user menutup pop-up tapi belum bayar (misal VA)
            return view('payment.pending', compact('orderId'));
        } else {
            // Jika gagal, kadaluarsa, atau dibatalkan ('deny', 'expire', 'cancel')
            return view('payment.rejected', compact('orderId'));
        }
    }
}

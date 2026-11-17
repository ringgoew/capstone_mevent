<?php

namespace App\Http\Controllers\EO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PayoutController extends Controller
{
    // Menampilkan halaman utama Payout EO (Riwayat & Tombol Ajukan)
    public function index()
    {
        // Ambil ID dari session kustom EO
        $userId = session('eo_user_id');

        // Jika belum login/sesi habis, arahkan ke login EO
        if (!$userId) {
            return redirect()->route('eo.login')->with('error', 'Sesi login habis, silahkan login ulang.');
        }

        // Ambil riwayat pengajuan payout EO ini
        $payouts = DB::table('payouts')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total pendapatan dari event milik EO ini yang statusnya 'paid'
        $totalRevenue = DB::table('orders')
            ->join('events', 'orders.event_id', '=', 'events.id')
            ->where('events.user_id', $userId)
            ->where('orders.status', 'paid')
            ->sum('orders.grand_total');

        // Hitung total yang sudah dicairkan/diajukan sebelumnya agar sisa saldo akurat
        $totalRequested = DB::table('payouts')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved', 'transferred'])
            ->sum('gross_amount');

        $availableBalance = max(0, $totalRevenue - $totalRequested);

        return view('eo.payout.index', compact('payouts', 'availableBalance', 'totalRevenue'));
    }

    // Menyimpan pengajuan payout baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'amount'                => 'required|numeric|min:10000',
            'bank_name'             => 'required|string|max:100',
            'bank_account_number'   => 'required|string|max:50',
            'bank_account_holder'   => 'required|string|max:100',
            'event_id'              => 'nullable|integer',
        ]);

        // Ambil ID dari session kustom EO
        $userId = session('eo_user_id');

        if (!$userId) {
            return redirect()->route('eo.login')->with('error', 'Sesi login habis, silahkan login ulang.');
        }

        $grossAmount = $request->amount;
        $commissionFee = 0;
        $netAmount = $grossAmount - $commissionFee;

        $payoutCode = 'PAY-' . strtoupper(Str::random(10));

        DB::table('payouts')->insert([
            'payout_code'         => $payoutCode,
            'user_id'             => $userId,
            'event_id'            => $request->event_id,
            'gross_amount'        => $grossAmount,
            'commission_fee'      => $commissionFee,
            'net_amount'          => $netAmount,
            'bank_name'           => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_holder' => $request->bank_account_holder,
            'status'              => 'pending',
            'requested_at'        => now(),
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        return redirect()->route('eo.payout.index')->with('success', 'Pengajuan payout berhasil dikirim! Menunggu verifikasi Admin.');
    }
}

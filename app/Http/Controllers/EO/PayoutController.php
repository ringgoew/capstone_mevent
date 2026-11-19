<?php

namespace App\Http\Controllers\EO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PayoutController extends Controller
{
    // Menampilkan halaman utama Payout EO (Saldo Per Event, Riwayat & Form Ajukan)
    public function index()
    {
        $userId = session('eo_user_id');

        if (!$userId) {
            return redirect()->route('eo.login')->with('error', 'Sesi login habis, silahkan login ulang.');
        }

        $eventNameCol = Schema::hasColumn('events', 'nama') ? 'nama' : 'title';

        // 1. Ambil daftar event milik EO ini beserta rincian saldo masing-masing event
        $events = DB::table('events')
            ->where('user_id', $userId)
            ->select('id', "{$eventNameCol} as event_name")
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($event) {
                $gross = (float) DB::table('orders')
                    ->where('event_id', $event->id)
                    ->where('status', 'paid')
                    ->sum('grand_total');

                $requested = (float) DB::table('payouts')
                    ->where('event_id', $event->id)
                    ->whereIn('status', ['pending', 'approved', 'completed', 'transferred'])
                    ->sum('gross_amount');

                $event->gross_revenue     = $gross;
                $event->available_balance = max(0, $gross - $requested);

                return $event;
            });

        $totalRevenue     = $events->sum('gross_revenue');
        $availableBalance = $events->sum('available_balance');

        // 2. Ambil riwayat pengajuan payout EO beserta nama event-nya
        $payouts = DB::table('payouts')
            ->leftJoin('events', 'payouts.event_id', '=', 'events.id')
            ->where('payouts.user_id', $userId)
            ->select('payouts.*', "events.{$eventNameCol} as event_name")
            ->orderBy('payouts.created_at', 'desc')
            ->get();

        return view('eo.payout.index', compact('events', 'payouts', 'availableBalance', 'totalRevenue'));
    }

    // Menyimpan pengajuan payout baru per event ke database
    public function store(Request $request)
    {
        $request->validate([
            'event_id'            => 'required|integer|exists:events,id',
            'amount'              => 'required|numeric|min:10000',
            'bank_name'           => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_holder' => 'required|string|max:100',
        ], [
            'event_id.required' => 'Silakan pilih event yang ingin dicairkan dananya.',
        ]);

        $userId = session('eo_user_id');

        if (!$userId) {
            return redirect()->route('eo.login')->with('error', 'Sesi login habis, silahkan login ulang.');
        }

        // Pastikan event yang dipilih benar-benar milik EO yang sedang login
        $event = DB::table('events')
            ->where('id', $request->event_id)
            ->where('user_id', $userId)
            ->first();

        if (!$event) {
            return back()->withInput()->with('error', 'Event tidak valid atau bukan milik akun Anda.');
        }

        // Hitung saldo tersedia khusus untuk event yang dipilih
        $grossEvent = (float) DB::table('orders')
            ->where('event_id', $event->id)
            ->where('status', 'paid')
            ->sum('grand_total');

        $requestedEvent = (float) DB::table('payouts')
            ->where('event_id', $event->id)
            ->whereIn('status', ['pending', 'approved', 'completed', 'transferred'])
            ->sum('gross_amount');

        $availableEventBalance = max(0, $grossEvent - $requestedEvent);

        if ((float) $request->amount > $availableEventBalance) {
            return back()->withInput()->with(
                'error',
                'Nominal pengajuan melebihi saldo tersedia pada event ini (Maksimal: Rp ' . number_format($availableEventBalance, 0, ',', '.') . ').'
            );
        }

        $grossAmount   = (float) $request->amount;
        $commissionFee = 0;
        $netAmount     = $grossAmount - $commissionFee;
        $payoutCode    = 'PAY-' . strtoupper(Str::random(10));

        DB::table('payouts')->insert([
            'payout_code'         => $payoutCode,
            'user_id'             => $userId,
            'event_id'            => $event->id,
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

        return redirect()->route('eo.payout.index')->with('success', 'Pengajuan payout untuk event tersebut berhasil dikirim! Menunggu verifikasi Admin.');
    }
}

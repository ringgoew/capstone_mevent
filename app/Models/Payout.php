<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payout extends Model
{

    //   Mengambil seluruh daftar pengajuan payout beserta identitas EO & Admin pemroses
    public static function getAllPayouts()
    {
        return DB::table('payouts')
            ->join('users as eo', 'payouts.user_id', '=', 'eo.id')
            ->leftJoin('users as admin_user', 'payouts.processed_by', '=', 'admin_user.id')
            ->select(
                'payouts.*',
                'eo.name as eo_name',
                'eo.email as eo_email',
                'admin_user.name as processor_name'
            )
            ->orderByRaw("CASE WHEN payouts.status = 'pending' THEN 1 ELSE 2 END")
            ->orderBy('payouts.requested_at', 'desc')
            ->orderBy('payouts.created_at', 'desc')
            ->get();
    }

    //  Mengambil detail lengkap satu pengajuan payout
    public static function getPayoutDetail($id)
    {
        $payout = DB::table('payouts')
            ->join('users as eo', 'payouts.user_id', '=', 'eo.id')
            ->leftJoin('users as admin_user', 'payouts.processed_by', '=', 'admin_user.id')
            ->select(
                'payouts.*',
                'eo.name as eo_name',
                'eo.email as eo_email',
                'admin_user.name as processor_name'
            )
            ->where('payouts.id', $id)
            ->first();

        if ($payout) {
            if (!empty($payout->event_id)) {
                $event = DB::table('events')->where('id', $payout->event_id)->first();
                $payout->event_name = $event->nama ?? $event->title ?? 'Event #' . $payout->event_id;
            } else {
                $payout->event_name = '-';
            }
        }

        return $payout;
    }


    public static function getAuditData($eventId, $payoutId)
    {
        // 1. Tambahan validasi: Wajib memiliki event_id agar audit spesifik per event
        if (empty($eventId)) {
            return [
                'riwayat_transaksi'     => collect(),
                'total_pemasukan_event' => 0,
                'total_sudah_dicairkan' => 0,
                'saldo_kotor_tersedia'  => 0,
            ];
        }

        // 2. Tambahan ->select(...): Ambil kolom grand_total sebagai total_amount
        $riwayatTransaksi = DB::table('orders')
            ->where('event_id', $eventId)
            ->whereIn('status', ['paid', 'settlement', 'success'])
            ->select('orders.*', 'orders.grand_total as total_amount')
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total pemasukan kotor dari transaksi tiket valid
        $totalPemasukanEvent = (float) $riwayatTransaksi->sum('total_amount');

        // 3. Tambahan status 'transferred' di whereIn
        $totalSudahDicairkan = (float) DB::table('payouts')
            ->where('event_id', $eventId)
            ->where('id', '!=', $payoutId)
            ->whereIn('status', ['completed', 'approved', 'transferred'])
            ->sum('gross_amount');

        $saldoKotorTersedia = $totalPemasukanEvent - $totalSudahDicairkan;

        return [
            'riwayat_transaksi'     => $riwayatTransaksi,
            'total_pemasukan_event' => $totalPemasukanEvent,
            'total_sudah_dicairkan' => $totalSudahDicairkan,
            'saldo_kotor_tersedia'  => $saldoKotorTersedia,
        ];
    }


    // Menyimpan hasil audit & memperbarui status transfer berhasil
    public static function simpanApprovalDanTransfer($id, $adminId, $proofOfTransfer)
    {
        return DB::table('payouts')
            ->where('id', $id)
            ->update([
                'status'            => 'completed',
                'proof_of_transfer' => $proofOfTransfer,
                'processed_by'      => $adminId,
                'processed_at'      => now(),
                'updated_at'        => now(),
            ]);
    }


    // Menyimpan hasil audit & alasan penolakan
    public static function simpanPenolakan($id, $adminId, $alasanPenolakan)
    {
        return DB::table('payouts')
            ->where('id', $id)
            ->update([
                'status'           => 'rejected',
                'rejection_reason' => $alasanPenolakan,
                'processed_by'     => $adminId,
                'processed_at'     => now(),
                'updated_at'       => now(),
            ]);
    }
}

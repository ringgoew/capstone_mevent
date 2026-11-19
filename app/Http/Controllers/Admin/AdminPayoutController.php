<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminPayoutController extends Controller
{
    /**
     * Menampilkan daftar pengajuan payout
     */
    public function index()
    {
        $payouts = Payout::getAllPayouts();
        return view('Admin.Payout.index', compact('payouts'));
    }

    /**
     * Menampilkan detail pengajuan payout & hasil pemeriksaan audit
     */
    public function show($id)
    {
        $payout = Payout::getPayoutDetail($id);

        if (!$payout) {
            return redirect()->route('admin.payout.index')
                ->with('error', 'Data pengajuan payout tidak ditemukan.');
        }

        // Audit transaksi & saldo berdasarkan event_id
        $audit = Payout::getAuditData($payout->event_id, $payout->id);

        $grossAmount   = (float) $payout->gross_amount;
        $commissionFee = (float) $payout->commission_fee;
        $netAmount     = (float) $payout->net_amount;

        // Cek 1: Apakah saldo kotor event mencukupi untuk gross_amount yang diajukan?
        $isSaldoCukup = ($audit['saldo_kotor_tersedia'] >= $grossAmount) && ($grossAmount > 0);

        // Cek 2: Apakah perhitungan net_amount = gross_amount - commission_fee akurat?
        $isHitunganValid = abs(($grossAmount - $commissionFee) - $netAmount) < 0.01 && ($netAmount > 0);

        $isAuditValid = $isSaldoCukup && $isHitunganValid;

        return view('Admin.Payout.show', compact('payout', 'audit', 'isSaldoCukup', 'isHitunganValid', 'isAuditValid'));
    }

    /**
     * Admin menyetujui payout -> Sistem teruskan ke Payment Gateway -> Update berhasil -> Notifikasi EO
     */
    public function approve(Request $request, $id)
    {
        $payout = Payout::getPayoutDetail($id);

        if (!$payout || $payout->status !== 'pending') {
            return redirect()->route('admin.payout.index')
                ->with('error', 'Pengajuan payout tidak valid atau sudah diproses sebelumnya.');
        }

        // Validasi ulang saldo dan nominal bersih sebelum eksekusi transfer
        $audit = Payout::getAuditData($payout->event_id, $payout->id);
        $grossAmount = (float) $payout->gross_amount;
        $netAmount   = (float) $payout->net_amount;

        if ($audit['saldo_kotor_tersedia'] < $grossAmount || $netAmount <= 0) {
            return back()->with('error', 'Audit Gagal: Saldo transaksi event tidak mencukupi atau nominal bersih tidak valid.');
        }

        DB::beginTransaction();
        try {
            // 1. Meneruskan permintaan transfer dana (net_amount) ke Payment Gateway
            $gatewayResponse = $this->prosesTransferPaymentGateway($payout);

            if ($gatewayResponse['status'] !== 'success') {
                DB::rollBack();
                return back()->with('error', 'Payment Gateway gagal memproses transfer: ' . $gatewayResponse['message']);
            }

            // 2. Simpan kode referensi / bukti transfer ke proof_of_transfer & ubah status menjadi completed
            Payout::simpanApprovalDanTransfer(
                $id,
                Auth::id(),
                $gatewayResponse['reference_id']
            );

            // 3. Kirim notifikasi status payout ke EO (user_id)
            $this->kirimNotifikasiKeEO($payout, 'approved', $gatewayResponse['reference_id']);

            DB::commit();

            return redirect()->route('admin.payout.index')
                ->with('success', 'Payout ' . $payout->payout_code . ' disetujui! Dana bersih Rp ' . number_format($netAmount, 0, ',', '.') . ' berhasil ditransfer (Ref: ' . $gatewayResponse['reference_id'] . ').');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses transfer: ' . $e->getMessage());
        }
    }

    /**
     * Admin menolak pengajuan payout & mengisi alasan penolakan
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:5',
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $payout = Payout::getPayoutDetail($id);

        if (!$payout || $payout->status !== 'pending') {
            return redirect()->route('admin.payout.index')
                ->with('error', 'Pengajuan payout tidak valid atau sudah diproses sebelumnya.');
        }

        Payout::simpanPenolakan($id, Auth::id(), $request->rejection_reason);

        // Kirim notifikasi penolakan ke EO
        $this->kirimNotifikasiKeEO($payout, 'rejected', null, $request->rejection_reason);

        return redirect()->route('admin.payout.index')
            ->with('error', 'Pengajuan payout ' . $payout->payout_code . ' telah ditolak.');
    }

    /**
     * Simulasi / Integrasi API Transfer Dana ke Payment Gateway (Midtrans Iris)
     */
    private function prosesTransferPaymentGateway($payout)
    {
        return [
            'status'       => 'success',
            'reference_id' => 'PG-IRIS-' . strtoupper(Str::random(10)),
            'message'      => 'Transfer processed successfully',
        ];
    }

    /**
     * Mengirim notifikasi status payout kepada EO (user_id)
     */
    private function kirimNotifikasiKeEO($payout, $keputusan, $refId = null, $alasan = null)
    {
        if (DB::getSchemaBuilder()->hasTable('notifications')) {
            $pesan = $keputusan === 'approved'
                ? "Pengajuan payout {$payout->payout_code} sebesar Rp " . number_format((float) $payout->net_amount, 0, ',', '.') . " telah disetujui dan ditransfer (Ref: {$refId})."
                : "Pengajuan payout {$payout->payout_code} ditolak. Alasan: {$alasan}";

            DB::table('notifications')->insert([
                'user_id'    => $payout->user_id,
                'title'      => $keputusan === 'approved' ? 'Payout Berhasil Ditransfer' : 'Pengajuan Payout Ditolak',
                'message'    => $pesan,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use App\Models\OrganizerProfile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Mail;
use App\Mail\KycApprovedMail;
use App\Mail\KycRejectedMail;

class AdminKycController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_login')) {
            return redirect()->route('admin.login');
        }

        $status = $request->query('status');

        if (!in_array($status, ['pending', 'verified', 'rejected'])) {
            $status = null;
        }

        $profiles = OrganizerProfile::getForAdmin($status);

        return view('admin.kyc.index', compact('profiles', 'status'));
    }

    public function show(int $id)
    {
        if (!session('admin_login')) {
            return redirect()->route('admin.login');
        }

        $profile = OrganizerProfile::findForAdmin($id);

        abort_if(!$profile, 404, 'Profil KYC tidak ditemukan.');

        return view('admin.kyc.show', compact('profile'));
    }

    public function document(int $id, string $type)
    {
        if (!session('admin_login')) {
            return redirect()->route('admin.login');
        }

        $columns = [
            'ktp' => 'ktp_image',
            'npwp' => 'npwp_image',
        ];

        abort_unless(isset($columns[$type]), 404);

        $profile = OrganizerProfile::findForAdmin($id);

        abort_if(!$profile, 404, 'Profil KYC tidak ditemukan.');

        $path = $profile->{$columns[$type]};

        abort_if(!$path, 404, 'Dokumen belum tersedia.');

        $disk = Storage::disk('local');

        abort_unless($disk->exists($path), 404, 'File dokumen tidak ditemukan.');

        return response()->file($disk->path($path));
    }

    public function approve(int $id)
    {
        if (!session('admin_login')) {
            return redirect()->route('admin.login');
        }

        // 1. Ambil data profil untuk mendapatkan email dan nama EO
        $profile = OrganizerProfile::findForAdmin($id);
        abort_if(!$profile, 404, 'Profil KYC tidak ditemukan.');

        // 2. Update status ke database
        $updated = OrganizerProfile::updatePendingStatus($id, 'verified');

        if (!$updated) {
            return redirect()
                ->route('admin.kyc.show', $id)
                ->with('error', 'KYC tidak dapat diproses. Periksa status terbaru.');
        }

        Mail::to($profile->user_email)->send(new KycApprovedMail($profile));

        return redirect()
            ->route('admin.kyc.index')
            ->with('success', 'KYC EO berhasil diverifikasi dan email notifikasi telah dikirim.');
    }

    public function reject(Request $request, int $id)
    {
        if (!session('admin_login')) {
            return redirect()->route('admin.login');
        }

        $data = $request->validate([
            'rejection_reason' => 'required|string|max:2000',
        ]);

        // 1. Ambil data profil untuk email
        $profile = OrganizerProfile::findForAdmin($id);
        abort_if(!$profile, 404, 'Profil KYC tidak ditemukan.');

        // 2. Update status ke database
        $updated = OrganizerProfile::updatePendingStatus($id, 'rejected', $data['rejection_reason']);

        if (!$updated) {
            return redirect()
                ->route('admin.kyc.show', $id)
                ->with('error', 'KYC tidak dapat diproses. Periksa status terbaru.');
        }

        // 3. Kirim Email Notifikasi & Alasan Penolakan
        Mail::to($profile->user_email)->send(new KycRejectedMail($profile, $data['rejection_reason']));

        return redirect()
            ->route('admin.kyc.index')
            ->with('success', 'KYC EO ditolak dan email penolakan telah dikirim.');
    }
}

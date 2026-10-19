<?php

namespace App\Http\Controllers;

use App\Models\OrganizerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminKycController extends Controller
{
    // Daftar EO yang perlu diperiksa
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

    // Detail dokumen dan profil EO
    public function show(int $id)
    {
        if (!session('admin_login')) {
            return redirect()->route('admin.login');
        }

        $profile = OrganizerProfile::findForAdmin($id);

        abort_if(!$profile, 404, 'Profil KYC tidak ditemukan.');

        return view('admin.kyc.show', compact('profile'));
    }

    // Melihat dokumen melalui route yang dilindungi
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

        // Asumsi dokumen disimpan di disk local/private.
        $disk = Storage::disk('local');

        abort_unless($disk->exists($path), 404, 'File dokumen tidak ditemukan.');

        return response()->file($disk->path($path));
    }

    // Setujui KYC EO
    public function approve(int $id)
    {
        if (!session('admin_login')) {
            return redirect()->route('admin.login');
        }

        $updated = OrganizerProfile::updatePendingStatus(
            $id,
            'verified'
        );

        if (!$updated) {
            return redirect()
                ->route('admin.kyc.show', $id)
                ->with('error', 'KYC tidak dapat diproses. Periksa status terbaru.');
        }

        return redirect()
            ->route('admin.kyc.index')
            ->with('success', 'KYC EO berhasil diverifikasi.');
    }

    // Tolak KYC dengan alasan wajib
    public function reject(Request $request, int $id)
    {
        if (!session('admin_login')) {
            return redirect()->route('admin.login');
        }

        $data = $request->validate([
            'rejection_reason' => 'required|string|max:2000',
        ]);

        $updated = OrganizerProfile::updatePendingStatus(
            $id,
            'rejected',
            $data['rejection_reason']
        );

        if (!$updated) {
            return redirect()
                ->route('admin.kyc.show', $id)
                ->with('error', 'KYC tidak dapat diproses. Periksa status terbaru.');
        }

        return redirect()
            ->route('admin.kyc.index')
            ->with('success', 'KYC EO ditolak. Alasan penolakan telah disimpan.');
    }
}

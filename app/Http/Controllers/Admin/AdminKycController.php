<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 

use App\Models\OrganizerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

<?php

namespace App\Http\Controllers\EO;

use App\Http\Controllers\Controller; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EOKycController extends Controller
{
    public function otp()
    {
        if (!session('eo_user_id')) {
            return redirect()->route('eo.register');
        }

        return view('eo.registration.otp');
    }

    public function verifyOtp(Request $request)
{
    if (!session('eo_user_id')) {
        return redirect()->route('eo.register');
    }

    $request->validate([
        'otp' => 'required|digits:6',
    ]);

    $otpHash = session('eo_otp_hash');
    $expiresAt = session('eo_otp_expires_at');

    if (!$otpHash || !$expiresAt) {
        return back()->with(
            'error',
            'OTP tidak tersedia. Silakan daftar kembali.'
        );
    }

    if (now()->timestamp > $expiresAt) {
        session()->forget([
            'eo_otp_hash',
            'eo_otp_expires_at',
        ]);

        return back()->with(
            'error',
            'Kode OTP sudah kedaluwarsa. Silakan daftar kembali.'
        );
    }

    if (!hash_equals($otpHash, hash('sha256', $request->otp))) {
        return back()->with(
            'error',
            'Kode OTP salah. Silakan coba lagi.'
        );
    }

    session([
        'eo_otp_verified' => true,
    ]);

    session()->forget([
        'eo_otp_hash',
        'eo_otp_expires_at',
    ]);

    return redirect()->route('eo.kyc');
}

    public function kyc()
    {
        if (
            !session('eo_user_id') ||
            !session('eo_otp_verified')
        ) {
            return redirect()->route('eo.register');
        }

        return view('eo.registration.kyc');
    }

    public function submitKyc(Request $request)
    {
        if (
            !session('eo_user_id') ||
            !session('eo_otp_verified')
        ) {
            return redirect()->route('eo.register');
        }

        $request->validate([
            'organization_name' => 'required|string|max:255',
            'ktp_number' => 'required|string|max:255',
            'ktp_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'npwp_number' => 'nullable|string|max:255',
            'npwp_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:255',
            'bank_account_holder' => 'required|string|max:255',
        ]);

        $userId = session('eo_user_id');

        $user = DB::table('users')
            ->where('id', $userId)
            ->where('role', 'organizer')
            ->first();

        if (!$user) {
            session()->forget([
                'eo_user_id',
                'eo_nama',
                'eo_email',
                'eo_otp_verified',
            ]);

            return redirect()->route('eo.register');
        }

        $existingProfile = DB::table('organizer_profiles')
            ->where('user_id', $userId)
            ->first();

        if ($existingProfile) {
            session()->forget('eo_otp_verified');

            return redirect()->route('eo.pending');
        }

        $ktpPath = null;
        $npwpPath = null;

        try {
            $ktpPath = $request->file('ktp_image')
                ->store('kyc/ktp', 'local');

            if ($request->hasFile('npwp_image')) {
                $npwpPath = $request->file('npwp_image')
                    ->store('kyc/npwp', 'local');
            }

            DB::table('organizer_profiles')->insert([
                'user_id' => $userId,
                'organization_name' => $request->organization_name,
                'ktp_number' => $request->ktp_number,
                'ktp_image' => $ktpPath,
                'npwp_number' => $request->npwp_number,
                'npwp_image' => $npwpPath,
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_account_holder' => $request->bank_account_holder,
                'verification_status' => 'pending',
                'rejection_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            if ($ktpPath) {
                Storage::disk('local')->delete($ktpPath);
            }

            if ($npwpPath) {
                Storage::disk('local')->delete($npwpPath);
            }

            throw $e;
        }

        session()->forget('eo_otp_verified');

        return redirect()->route('eo.pending');
    }

    // Halaman status verifikasi
    public function pending()
    {
        $userId = session('eo_user_id');

        if (!$userId) {
            return redirect()->route('eo.register');
        }

        $profile = DB::table('organizer_profiles')
            ->where('user_id', $userId)
            ->first();

        return view('eo.registration.pending', compact('profile'));
    }
}
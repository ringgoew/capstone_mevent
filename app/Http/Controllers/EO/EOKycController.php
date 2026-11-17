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

        // Simpan waktu verifikasi email ke database agar permanen lintas sesi
        DB::table('users')
            ->where('id', session('eo_user_id'))
            ->whereNull('email_verified_at')
            ->update([
                'email_verified_at' => now(),
                'updated_at'        => now(),
            ]);

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
        $userId = session('eo_user_id');

        if (!$userId) {
            return redirect()->route('eo.register');
        }

        $user = DB::table('users')
            ->where('id', $userId)
            ->where('role', 'organizer')
            ->first();

        if (!$user) {
            session()->forget(['eo_user_id', 'eo_nama', 'eo_email', 'eo_otp_verified']);
            return redirect()->route('eo.register');
        }

        if ($user->status === 'verified') {
            session()->forget('eo_otp_verified');
            return redirect()->route('eo.dashboard');
        }

        $profile = DB::table('organizer_profiles')
            ->where('user_id', $userId)
            ->first();

        if ($profile && $profile->verification_status === 'pending') {
            return redirect()->route('eo.pending');
        }

        // Izinkan akses form KYC jika OTP terverifikasi di sesi, email_verified_at terisi, atau status rejected
        $isEmailVerified = !is_null($user->email_verified_at);
        $isRejected = $profile && $profile->verification_status === 'rejected';

        if (!session('eo_otp_verified') && !$isEmailVerified && !$isRejected) {
            return redirect()->route('eo.otp');
        }

        return view('eo.registration.kyc', compact('profile'));
    }

    public function submitKyc(Request $request)
    {
        $userId = session('eo_user_id');

        if (!$userId) {
            return redirect()->route('eo.register');
        }

        $user = DB::table('users')
            ->where('id', $userId)
            ->where('role', 'organizer')
            ->first();

        if (!$user) {
            session()->forget(['eo_user_id', 'eo_nama', 'eo_email', 'eo_otp_verified']);
            return redirect()->route('eo.register');
        }

        $existingProfile = DB::table('organizer_profiles')
            ->where('user_id', $userId)
            ->first();

        if ($existingProfile && $existingProfile->verification_status === 'verified') {
            session()->forget('eo_otp_verified');
            return redirect()->route('eo.dashboard');
        }

        $isEmailVerified = !is_null($user->email_verified_at);
        $isRejected = $existingProfile && $existingProfile->verification_status === 'rejected';

        if (!session('eo_otp_verified') && !$isEmailVerified && !$isRejected) {
            return redirect()->route('eo.otp');
        }

        $ktpRequired = (!$existingProfile || !$existingProfile->ktp_image) ? 'required' : 'nullable';

        $request->validate([
            'organization_name'   => 'required|string|max:255',
            'ktp_number'          => 'required|string|max:255',
            'ktp_image'           => "$ktpRequired|image|mimes:jpg,jpeg,png|max:2048",
            'npwp_number'         => 'nullable|string|max:255',
            'npwp_image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bank_name'           => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:255',
            'bank_account_holder' => 'required|string|max:255',
        ]);

        $ktpPath = $existingProfile->ktp_image ?? null;
        $npwpPath = $existingProfile->npwp_image ?? null;

        try {
            if ($request->hasFile('ktp_image')) {
                $ktpPath = $request->file('ktp_image')->store('kyc/ktp', 'local');
                if ($existingProfile && $existingProfile->ktp_image) {
                    Storage::disk('local')->delete($existingProfile->ktp_image);
                }
            }

            if ($request->hasFile('npwp_image')) {
                $npwpPath = $request->file('npwp_image')->store('kyc/npwp', 'local');
                if ($existingProfile && $existingProfile->npwp_image) {
                    Storage::disk('local')->delete($existingProfile->npwp_image);
                }
            }

            DB::transaction(function () use ($request, $userId, $existingProfile, $ktpPath, $npwpPath) {
                $dataToSave = [
                    'organization_name'   => $request->organization_name,
                    'ktp_number'          => $request->ktp_number,
                    'ktp_image'           => $ktpPath,
                    'npwp_number'         => $request->npwp_number,
                    'npwp_image'          => $npwpPath,
                    'bank_name'           => $request->bank_name,
                    'bank_account_number' => $request->bank_account_number,
                    'bank_account_holder' => $request->bank_account_holder,
                    'verification_status' => 'pending',
                    'rejection_reason'    => null,
                    'updated_at'          => now(),
                ];

                if ($existingProfile) {
                    DB::table('organizer_profiles')
                        ->where('user_id', $userId)
                        ->update($dataToSave);
                } else {
                    $dataToSave['user_id'] = $userId;
                    $dataToSave['created_at'] = now();
                    DB::table('organizer_profiles')->insert($dataToSave);
                }

                DB::table('users')
                    ->where('id', $userId)
                    ->update([
                        'status'     => 'pending',
                        'updated_at' => now(),
                    ]);
            });
        } catch (\Throwable $e) {
            if ($request->hasFile('ktp_image') && $ktpPath) {
                Storage::disk('local')->delete($ktpPath);
            }
            if ($request->hasFile('npwp_image') && $npwpPath) {
                Storage::disk('local')->delete($npwpPath);
            }

            throw $e;
        }

        session()->forget('eo_otp_verified');

        return redirect()->route('eo.pending');
    }
    
    public function pending()
    {
        $userId = session('eo_user_id');

        if (!$userId) {
            return redirect()->route('eo.register');
        }

        $user = DB::table('users')
            ->where('id', $userId)
            ->where('role', 'organizer')
            ->first();

        if ($user && $user->status === 'verified') {
            return redirect()->route('eo.dashboard');
        }

        $profile = DB::table('organizer_profiles')
            ->where('user_id', $userId)
            ->first();

        return view('eo.registration.pending', compact('profile'));
    }
}

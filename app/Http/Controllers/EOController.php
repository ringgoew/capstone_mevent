<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EOController extends Controller
{
    // Halaman registrasi EO
    public function register()
    {
        return view('eo.register');
    }

    // Simpan akun EO
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'no_telp' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $userId = DB::table('users')->insertGetId([
            'name' => $request->penanggung_jawab,
            'email' => $request->email,
            'phone_number' => $request->no_telp,
            'password' => Hash::make($request->password),
            'role' => 'organizer',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session([
            'eo_user_id' => $userId,
            'eo_nama' => $request->nama,
            'eo_email' => $request->email,
            'eo_otp_verified' => false,
        ]);

        return redirect()->route('eo.otp');
    }

    // Halaman OTP
    public function otp()
    {
        if (!session('eo_user_id')) {
            return redirect()->route('eo.register');
        }

        return view('eo.otp');
    }

    // Verifikasi OTP dummy
    public function verifyOtp(Request $request)
    {
        if (!session('eo_user_id')) {
            return redirect()->route('eo.register');
        }

        $request->validate([
            'otp' => 'required|string',
        ]);

        if ($request->otp === '123456') {
            session(['eo_otp_verified' => true]);

            return redirect()->route('eo.kyc');
        }

        return back()->with('error', 'Kode OTP salah. Silakan coba lagi.');
    }

    // Halaman KYC
    public function kyc()
    {
        if (
            !session('eo_user_id') ||
            !session('eo_otp_verified')
        ) {
            return redirect()->route('eo.register');
        }

        return view('eo.kyc');
    }

    // Simpan profil dan dokumen KYC
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

        return view('eo.pending', compact('profile'));
    }

    // Halaman login EO
    public function login()
    {
        return view('eo.login');
    }

    // Proses login EO
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = DB::table('users')
            ->where('email', $request->email)
            ->where('role', 'organizer')
            ->first();

        if (
            !$user ||
            !Hash::check($request->password, $user->password)
        ) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');
        }

        if ($user->status !== 'active') {
            return back()->with('error', 'Akun EO tidak aktif.');
        }

        $profile = DB::table('organizer_profiles')
            ->where('user_id', $user->id)
            ->first();

        if (!$profile) {
            session([
                'eo_user_id' => $user->id,
                'eo_nama' => $user->name,
                'eo_email' => $user->email,
                'eo_otp_verified' => true,
            ]);

            return redirect()->route('eo.kyc');
        }

        if ($profile->verification_status === 'rejected') {
            return back()->with(
                'error',
                'Verifikasi ditolak: ' .
                ($profile->rejection_reason ?? 'Silakan hubungi admin.')
            );
        }

        if ($profile->verification_status !== 'verified') {
            return back()->with(
                'error',
                'Akun EO masih menunggu persetujuan admin.'
            );
        }

        $request->session()->regenerate();

        session([
            'eo_login' => true,
            'eo_user_id' => $user->id,
            'eo_nama' => $user->name,
            'eo_email' => $user->email,
        ]);

        return redirect()->route('eo.dashboard');
    }

    // Dashboard EO (statistik masih dummy)
    public function dashboard()
    {
        if (!session('eo_login')) {
            return redirect()->route('eo.login');
        }

        $stats = [
            'total_event' => 2,
            'tiket_terjual' => 125,
            'pendapatan' => 6250000,
            'peserta_hadir' => 80,
        ];

        $events = [
            [
                'nama' => 'E-Fest',
                'tanggal' => '15 Juni 2027',
                'lokasi' => 'Jubung',
                'tiket_terjual' => 80,
                'status' => 'Published',
            ],
            [
                'nama' => 'Civil Day',
                'tanggal' => '15 Juni 2027',
                'lokasi' => 'Tanjung Bunga',
                'tiket_terjual' => 45,
                'status' => 'Published',
            ],
        ];

        return view('eo.dashboard', compact('stats', 'events'));
    }

    // Logout EO
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
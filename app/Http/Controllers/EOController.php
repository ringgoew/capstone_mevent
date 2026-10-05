<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EOController extends Controller
{
    // Halaman registrasi akun EO
    public function register()
    {
        return view('eo.register');
    }

    // Setelah registrasi, lanjut ke verifikasi OTP
    public function store(Request $request)
    {
        // Dummy: simpan sementara data registrasi di session
        session([
            'eo_nama' => $request->nama,
            'eo_penanggung_jawab' => $request->penanggung_jawab,
            'eo_email' => $request->email,
            'eo_no_telp' => $request->no_telp,
            'eo_password' => $request->password,
        ]);

        return redirect()->route('eo.otp');
    }

    // Halaman input OTP
    public function otp()
    {
        return view('eo.otp');
    }

    // Proses verifikasi OTP
    public function verifyOtp(Request $request)
    {
        // Dummy OTP
        if ($request->otp == '123456') {
            return redirect()->route('eo.kyc');
        }

        return back()->with('error', 'Kode OTP salah. Silakan coba lagi.');
    }

    // Halaman melengkapi profil dan dokumen KYC
    public function kyc()
    {
        return view('eo.kyc');
    }

    // Proses pengiriman berkas pendaftaran
    public function submitKyc(Request $request)
    {
        // Dummy: belum disimpan ke database

        session([
            'eo_status' => 'Pending Approval'
        ]);

        return redirect()->route('eo.pending');
    }

    // Halaman menunggu verifikasi
    public function pending()
    {
        return view('eo.pending');
    }

    // Halaman login EO
public function login()
{
    return view('eo.login');
}

// Proses login EO
public function authenticate(Request $request)
{
    // Dummy login
    if (
        $request->email == 'eo@pookiemax.com' &&
        $request->password == '123456'
    ) {
        session([
            'eo_login' => true,
            'eo_nama' => 'Event POOKIEMAX'
        ]);

        return redirect()->route('eo.dashboard');
    }

    return back()->with('error', 'Email atau password salah.');
    }
    // Dashboard EO
    public function dashboard()
    {
        // Dummy data untuk dashboard
        $stats = [
            'total_event' => 2,
            'tiket_terjual' => 125,
            'pendapatan' => 6250000,
            'peserta_hadir' => 80,
        ];

        // Dummy data event
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
}
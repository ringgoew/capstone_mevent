<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanitiaDashboardController extends Controller
{
    /**
     * Menampilkan halaman utama (Dashboard) khusus Panitia.
     */
    public function index()
    {
        // Anda bisa menambahkan logika lain di sini nanti,
        // misalnya mengambil data jumlah tiket yang sudah di-scan oleh panitia ini.

        return view('eo.panitia.dashboard');
    }
}

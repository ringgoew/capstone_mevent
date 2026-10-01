<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EOController extends Controller
{
    public function register()
    {
        return view('eo.register');
    }

    public function store(Request $request)
    {
        // Logic for storing EO registration data would go here
        return redirect()->route('eo.register')->with('success', 'Pendaftaran EO berhasil dikirim menunggu verifikasi.');
    }
}

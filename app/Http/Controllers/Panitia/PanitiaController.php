<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Panitia; // Import model yang baru dibuat
use Illuminate\Support\Facades\Hash;

class PanitiaController extends Controller
{
    public function index()
    {
        // Memanggil Query Builder dari Model
        $panitia = Panitia::getDaftarPanitia();

        return view('eo.panitia.index', compact('panitia'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        // Siapkan array data, lalu kirim ke fungsi tambahPanitia di Model
        $dataPanitia = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        Panitia::tambahPanitia($dataPanitia);

        return redirect()->route('panitia.index')
            ->with('success', 'Berhasil: Akun panitia baru telah ditambahkan.');
    }

    public function destroy($id)
    {
        // Memanggil Query Builder delete dari Model
        Panitia::hapusPanitia($id);

        return redirect()->route('panitia.index')
            ->with('success', 'Berhasil: Akun panitia telah dihapus.');
    }
}

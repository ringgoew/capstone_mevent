<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Panitia;
use Illuminate\Support\Facades\Hash;

class PanitiaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $dataPanitia = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'panitia',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        Panitia::tambahPanitia($dataPanitia);

        return redirect()->back()
            ->with('success', 'Berhasil: Akun panitia baru telah ditambahkan.');
    }

    public function destroy($id)
    {
        Panitia::hapusPanitia($id);

        return redirect()->back()
            ->with('success', 'Berhasil: Akun panitia telah dihapus.');
    }
}

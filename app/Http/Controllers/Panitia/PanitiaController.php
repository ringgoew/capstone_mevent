<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Panitia;
use Illuminate\Http\Request;

class PanitiaController extends Controller
{
    public function store(Request $request)
    {
        $eoId = session('eo_user_id');

        if (!$eoId) {
            return redirect()->route('eo.login')
                ->with('error', 'Sesi login EO telah berakhir, silakan login ulang.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        Panitia::tambahPanitia($validated, $eoId);

        return redirect()->back()
            ->with('success', 'Berhasil: Akun panitia baru telah ditambahkan.');
    }

    public function destroy($id)
    {
        $eoId = session('eo_user_id');

        if (!$eoId) {
            return redirect()->route('eo.login')
                ->with('error', 'Sesi login EO telah berakhir, silakan login ulang.');
        }

        $deleted = Panitia::hapusPanitia($id, $eoId);

        if (!$deleted) {
            return redirect()->back()
                ->with('error', 'Gagal: Akun panitia tidak ditemukan atau bukan milik akun EO Anda.');
        }

        return redirect()->back()
            ->with('success', 'Berhasil: Akun panitia telah dihapus.');
    }
}

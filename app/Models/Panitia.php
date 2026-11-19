<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Panitia extends Model
{
    // Mengambil daftar panitia khusus milik EO yang sedang login
    public static function getDaftarPanitia($eoId = null)
    {
        $ownerId = $eoId ?? session('eo_user_id');

        if (!$ownerId) {
            return collect();
        }

        return DB::table('users')
            ->where('role', 'panitia')
            ->where('eo_id', $ownerId)
            ->orderBy('id', 'desc')
            ->get();
    }

    // Menambahkan panitia baru ke kolom eo_id milik EO yang sedang login
    public static function tambahPanitia(array $data, $eoId = null)
    {
        $ownerId = $eoId ?? session('eo_user_id');

        $password = str_starts_with($data['password'], '$2y$')
            ? $data['password']
            : Hash::make($data['password']);

        return DB::table('users')->insert([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => $password,
            'role'       => 'panitia',
            'status'     => 'active',
            'eo_id'      => $ownerId,
            'created_at' => $data['created_at'] ?? now(),
            'updated_at' => $data['updated_at'] ?? now(),
        ]);
    }

    // Menghapus panitia hanya jika memang milik EO tersebut
    public static function hapusPanitia($id, $eoId = null)
    {
        $ownerId = $eoId ?? session('eo_user_id');

        if (!$ownerId) {
            return 0;
        }

        return DB::table('users')
            ->where('id', $id)
            ->where('role', 'panitia')
            ->where('eo_id', $ownerId)
            ->delete();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Panitia extends Model
{
    // Mengambil daftar panitia dari database
    public static function getDaftarPanitia()
    {
        return DB::table('users')
            ->where('role', '!=', 'EO')
            ->orderBy('id', 'desc')
            ->get();
    }


    // Menambahkan panitia baru ke database
    public static function tambahPanitia($data)
    {
        return DB::table('users')->insert($data);
    }

    // Menghapus panitia dari database
    public static function hapusPanitia($id)
    {
        return DB::table('users')->where('id', $id)->delete();
    }
}

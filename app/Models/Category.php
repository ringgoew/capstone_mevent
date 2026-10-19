<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Category
{
    // Mengambil semua kategori
    public function getAll()
    {
        return DB::table('categories')
            ->orderBy('id')
            ->get();
    }

    // Mengambil kategori berdasarkan ID
    public function getById($id)
    {
        return DB::table('categories')
            ->where('id', $id)
            ->first();
    }
}
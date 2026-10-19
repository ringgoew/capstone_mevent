<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Event
{
    // Mengambil semua event milik EO
    public function getByOrganizer($userId)
    {
        return DB::table('events')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();
    }

    // Mengambil detail event
    public function getById($id)
    {
        return DB::table('events')
            ->where('id', $id)
            ->first();
    }

    // Menyimpan draft event
    public function createDraft($data)
    {
        $data['status'] = 'draft';
        $data['created_at'] = now();
        $data['updated_at'] = now();

        return DB::table('events')->insertGetId($data);
    }

    // Mengubah status event
    public function updateStatus($id, $userId, $status)
    {
        return DB::table('events')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->update([
                'status' => $status,
                'updated_at' => now(),
            ]);
    }
}
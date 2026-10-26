<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class AdminEventModeration
{
    /**
     * Mengambil semua event yang menunggu pemeriksaan.
     */
    public static function getPendingEvents()
    {
        return DB::table('events as e')
            ->join('users as u', 'u.id', '=', 'e.user_id')
            ->join('event_categories as c', 'c.id', '=', 'e.category_id')
            ->select(
                'e.*',
                'u.name as eo_name',
                'u.email as eo_email',
                'c.name as category_name'
            )
            ->where('e.status', 'pending')
            ->orderBy('e.created_at', 'asc')
            ->get();
    }

    public static function findEvent(int $id): ?object
    {
        return DB::table('events as e')
            ->join('users as u', 'u.id', '=', 'e.user_id')
            ->join('event_categories as c', 'c.id', '=', 'e.category_id')
            ->select(
                'e.*',
                'u.name as eo_name',
                'u.email as eo_email',
                'c.name as category_name'
            )
            ->where('e.id', $id)
            ->first();
    }

    /**
     * Mengambil jenis tiket dan kuotanya.
     */
    public static function getTicketTypes(int $eventId)
    {
        return DB::table('ticket_types')
            ->where('event_id', $eventId)
            ->orderBy('price', 'asc')
            ->get();
    }

    /**
     * Mengubah status hanya jika event masih pending.
     * Menghindari event yang sama diproses ulang.
     */
    public static function updatePendingStatus(
        int $id,
        string $status,
        ?string $reason = null
    ): bool {
        if (!in_array($status, ['published', 'rejected'], true)) {
            return false;
        }

        return DB::table('events')
            ->where('id', $id)
            ->where('status', 'pending')
            ->update([
                'status' => $status,
                'rejection_reason' => $reason,
                'updated_at' => now(),
            ]) === 1;
    }
}

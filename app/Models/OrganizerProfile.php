<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class OrganizerProfile
{
    // Daftar KYC EO untuk Admin
    public static function getForAdmin(?string $status = null)
    {
        $query = DB::table('organizer_profiles as op')
            ->join('users as u', 'u.id', '=', 'op.user_id')
            ->select(
                'op.*',
                'u.name as user_name',
                'u.email as user_email'
            )
            ->orderByDesc('op.created_at');

        if (in_array($status, ['pending', 'verified', 'rejected'])) {
            $query->where('op.verification_status', $status);
        }

        return $query->get();
    }

    // Detail profil KYC berdasarkan ID
    public static function findForAdmin(int $id): ?object
    {
        return DB::table('organizer_profiles as op')
            ->join('users as u', 'u.id', '=', 'op.user_id')
            ->select(
                'op.*',
                'u.name as user_name',
                'u.email as user_email'
            )
            ->where('op.id', $id)
            ->first();
    }

    // Verifikasi atau tolak profil yang masih pending
    public static function updatePendingStatus(
        int $id,
        string $status,
        ?string $reason = null
    ): bool {
        return DB::table('organizer_profiles')
            ->where('id', $id)
            ->where('verification_status', 'pending')
            ->update([
                'verification_status' => $status,
                'rejection_reason' => $reason,
                'updated_at' => now(),
            ]) === 1;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrganizerProfile extends Model
{
    protected $table = 'organizer_profiles';

    protected $guarded = ['id'];

    public static function getForAdmin(?string $status = null)
    {
        return DB::table('organizer_profiles')
            ->join('users', 'organizer_profiles.user_id', '=', 'users.id')
            ->where('users.role', 'organizer')
            ->select(
                'organizer_profiles.*',
                'users.name as user_name',
                'users.email as user_email',
                'users.status as user_status'
            )
            ->when($status, function ($query, $status) {
                return $query->where('organizer_profiles.verification_status', $status);
            })
            ->orderByRaw("
                CASE organizer_profiles.verification_status
                    WHEN 'pending' THEN 1
                    WHEN 'rejected' THEN 2
                    WHEN 'verified' THEN 3
                    ELSE 4
                END
            ")
            ->orderByDesc('organizer_profiles.updated_at')
            ->get();
    }

    public static function findForAdmin(int $id)
    {
        return DB::table('organizer_profiles')
            ->join('users', 'organizer_profiles.user_id', '=', 'users.id')
            ->where('organizer_profiles.id', $id)
            ->select(
                'organizer_profiles.*',
                'users.name as user_name',
                'users.email as user_email',
                'users.status as user_status'
            )
            ->first();
    }

    public static function updatePendingStatus(int $id, string $newStatus, ?string $rejectionReason = null): bool
    {
        return DB::table('organizer_profiles')
            ->where('id', $id)
            ->where('verification_status', 'pending')
            ->update([
                'verification_status' => $newStatus,
                'rejection_reason' => $rejectionReason,
                'updated_at' => now(),
            ]) > 0;
    }
}

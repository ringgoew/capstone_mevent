<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizerProfile extends Model
{
    protected $table = 'organizer_profiles';

    protected $fillable = [
        'user_id',
        'organization_name',
        'ktp_number',
        'ktp_image',
        'npwp_number',
        'npwp_image',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'verification_status',
        'rejection_reason',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

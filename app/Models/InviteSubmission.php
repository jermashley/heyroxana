<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InviteSubmission extends Model
{
    protected $fillable = [
        'token',
        'date_type',
        'date_type_label',
        'scheduled_at',
        'message',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];
}

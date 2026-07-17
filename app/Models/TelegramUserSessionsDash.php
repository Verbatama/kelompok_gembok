<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramUserSessionsDash extends Model
{
    protected $fillable = [
        'chat_id',
        'session_type',
        'session_data',
        'current_step',
        'expires_at',
    ];

    protected $casts = [
        'session_data' => 'array',
        'expires_at' => 'datetime',
    ];
}

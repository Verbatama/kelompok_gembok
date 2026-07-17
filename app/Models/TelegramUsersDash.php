<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramUsersDash extends Model
{
    protected $fillable = [
        'chat_id',
        'username',
        'first_name',
        'last_name',
        'role',
        'is_active',
        'last_activity',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_activity' => 'datetime',
    ];
}

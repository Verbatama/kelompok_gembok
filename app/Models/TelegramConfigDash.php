<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramConfigDash extends Model
{
    protected $fillable = [
        'bot_token',
        'chat_id',
        'is_connected',
        'last_test',
    ];
}

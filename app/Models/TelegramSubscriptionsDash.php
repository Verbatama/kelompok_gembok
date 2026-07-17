<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramSubscriptionsDash extends Model
{
    protected $fillable = [
        'chat_id',
        'device_id',
        'device_serial',
        'subscribed_at',
        'is_active',
    ];

    public $timestamps = false;

    protected $casts = [
        'subscribed_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}

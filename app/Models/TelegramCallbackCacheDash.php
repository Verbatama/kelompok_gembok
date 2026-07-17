<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramCallbackCacheDash extends Model
{
    protected $fillable = [
        'cache_key',
        'cache_data',
        'created_at',
        'expires_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'cache_data' => 'array',
        'created_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}

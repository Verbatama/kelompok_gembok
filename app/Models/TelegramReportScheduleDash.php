<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramReportScheduleDash extends Model
{
    protected $fillable = [
        'chat_id',
        'report_type',
        'schedule_time',
        'schedule_day',
        'is_active',
        'last_sent_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'schedule_time' => 'datetime:H:i:s',
        'schedule_day' => 'integer',
        'is_active' => 'boolean',
        'last_sent_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public const TYPE_DAILY = 'daily';
    public const TYPE_WEEKLY = 'weekly';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramReportLogs extends Model
{
    protected $fillable = [
        'chat_id',
        'report_type',
        'report_date',
        'sent_at',
        'total_devices',
        'online_devices',
        'offline_devices',
        'new_online_count',
        'new_offline_count',
        'offline_24h_count',
        'poor_signal_count',
        'report_data',
    ];

    public $timestamps = false;

    protected $casts = [
        'report_date' => 'date',
        'sent_at' => 'datetime',
        'total_devices' => 'integer',
        'online_devices' => 'integer',
        'offline_devices' => 'integer',
        'new_online_count' => 'integer',
        'new_offline_count' => 'integer',
        'offline_24h_count' => 'integer',
        'poor_signal_count' => 'integer',
        'report_data' => 'array',
    ];

    public const TYPE_DAILY = 'daily';
    public const TYPE_WEEKLY = 'weekly';
}

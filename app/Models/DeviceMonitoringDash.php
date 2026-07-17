<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceMonitoringDash extends Model
//cek ulang
{
    protected $table = 'device_monitoring';

    public $timestamps = false;

    protected $fillable = [
        'device_id',
        'status',
        'notified',
        'created_at',
    ];

    protected $casts = [
        'notified' => 'boolean',
        'created_at' => 'datetime',
    ];
}

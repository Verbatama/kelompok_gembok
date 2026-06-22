<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiketGangguan extends Model
{
    use HasFactory;
    protected $table = 'ticket_gangguans'; 

    protected $fillable = [
        'customer_name',
        'customer_id',
        'jenis_gangguan',
        'prioritas',
        'keterangan',
        'connection_status',
        'pppoe_username',
        'ip_address',
        'mac_address',
        'last_update_connection',
    ];

    // Opsional: Jika Anda ingin otomatis mengubah string ke format tanggal/karbon
    protected $casts = [
        'last_update_connection' => 'datetime',
    ];
}
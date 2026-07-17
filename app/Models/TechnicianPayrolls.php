<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;  
class TechnicianPayrolls extends Model
{
     use HasFactory;

    protected $fillable = [
        'technician_id',
        'bulan',
        'tahun',
        'gaji_pokok',
        'jumlah_telat',
        'jumlah_absen',
        'denda_telat',
        'denda_absen',
        'bonus',
        'total_potongan',
        'total_diterima',
        'status',
        'processed_at',
        
    ];


    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}

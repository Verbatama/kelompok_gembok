<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicianAttendance extends Model
{
    protected $fillable = [
        'technician_id',
        'image_selfie',
        'status',
        'attendance_date',
        'latitude',
        'longitude',
        'is_late',
        'absent',
        'check_in_limit',
        'bonus_check_out_mulai',
        'bonus_check_out_selesai',
        'bonus_didapat',
    ];

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}

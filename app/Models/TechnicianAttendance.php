<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicianAttendance extends Model
{
    protected $fillable = [
        'technician_id',
        'image_selfie',
        'status',
        'latitude',
        'longitude',
        'is_late',
        'absent'
    ];

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}
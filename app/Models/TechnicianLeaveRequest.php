<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TechnicianLeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'technician_id',
        'leave_date',
        'type',
        'reason',
    ];

   
public function technician()
{
    return $this->belongsTo(Technician::class);
}
}
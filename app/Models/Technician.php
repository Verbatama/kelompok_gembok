<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    protected $fillable = [
        'name',
        'username',
        'phone',
        'role',
        'email',
        'password',
        'notes',
        'is_active',
        'area_coverage',
        'gaji_pokok',
        'join_date',
        'last_login',
        'whatsapp_group_id',
        'check_in_limit',
        'bonus_check_out_mulai',
        'bonus_check_out_selesai',
        
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'join_date' => 'datetime',
        'last_login' => 'datetime',
    ];

    public function attendances()
    {
        return $this->hasMany(TechnicianAttendance::class);
    }

    public function payrolls()
    {
        return $this->hasMany(TechnicianPayrolls::class);
    }

   
public function leaveRequests()
{
    return $this->hasMany(TechnicianLeaveRequest::class);
}
}

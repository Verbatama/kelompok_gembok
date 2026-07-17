<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAttendance extends Model
{
    protected $fillable = [
        'user_id',
        'image_selfie',
        'status',
        'latitude',
        'longitude',
        'check_in_limit',
        'bonus_check_out_mulai',
        'bonus_check_out_selesai',
        'bonus_didapat',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class,'user_id');
    }

    //Ini membuat $history->is_late bisa dipakai di view tanpa perlu kolom asli di database 
    public function getIsLateAttribute()
{
    if ($this->status !== 'check-in' || !$this->check_in_limit) {
        return null;
    }

    return \Carbon\Carbon::parse($this->created_at)->format('H:i:s') > $this->check_in_limit;
}
}
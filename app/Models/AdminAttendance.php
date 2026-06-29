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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
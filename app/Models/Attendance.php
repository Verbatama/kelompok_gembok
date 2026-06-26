<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = [
        'nama',
        'image_selfie',
        'latitude',
        'longitude',
        'status',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigurationGenieDash extends Model
{
    protected $fillable = [
        'config_key',
        'config_value',
    ];
}

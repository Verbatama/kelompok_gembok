<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenieCredentialsDash extends Model
{
    protected $fillable = [
        'host',
        'port',
        'username',
        'password',
        'role',
        'is_connected',
        'last_test',
    
    ];
}

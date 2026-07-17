<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MikrotikCredentialsDash extends Model
{
    protected $fillable = [
        'host',
        'port',
        'username',
        'password',
        'is_connected',
        'last_test',
    ];
}

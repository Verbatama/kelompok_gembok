<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectCustomer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'discount',
        'ktp_foto',
        'status',
    ];
}

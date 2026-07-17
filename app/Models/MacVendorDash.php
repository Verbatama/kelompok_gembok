<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MacVendorDash extends Model
{
    protected $table = 'mac_vendor_cache';

    protected $primaryKey = 'oui';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'oui',
        'vendor_name',
        'cached_at',
    ];

    protected $casts = [
        'cached_at' => 'datetime',
    ];
}

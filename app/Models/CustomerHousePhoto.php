<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerHousePhoto extends Model
{
    protected $fillable=[
        'customer_id',
        'photo'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
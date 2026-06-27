<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Radacct extends Model
{   
    protected $connection = 'freeradius';
    protected $table = 'radacct';
    protected $primaryKey = 'radacctid';

    public $timestamps = false;
 
}

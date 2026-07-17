<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramUserPermisionDash extends Model
{
    protected $table = 'telegram_user_permissions';

    public $timestamps = false;

    public $incrementing = false;

    protected $guarded = [];
}

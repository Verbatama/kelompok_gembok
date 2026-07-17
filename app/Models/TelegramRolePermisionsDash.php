<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramRolePermisionsDash extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'role',
        'permission_key',
    ];

    public const ROLE_ADMIN = 'admin';
    public const ROLE_OPERATOR = 'operator';
    public const ROLE_VIEWER = 'viewer';

    /**
     * Permission yang direferensikan oleh permission_key.
     */
    public function permission()
    {
        return $this->belongsTo(
            TelegramPermission::class,
            'permission_key',
            'permission_key'
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class TelegramPermisionsDash extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'permission_key',
        'permission_name',
        'description',
        'category',
    ];

    /**
     * Role yang memiliki permission ini.
     */
    // public function roles(): BelongsToMany
    // {
    //     return $this->belongsToMany(
    //         TelegramRole::class,
    //         'telegram_role_permissions',
    //         'permission_id',
    //         'role_id'
    //     );
    // }

    public function rolePermissions()
    {
        return $this->hasMany(
            TelegramRolePermission::class,
            'permission_key',
            'permission_key'
        );
    }
}

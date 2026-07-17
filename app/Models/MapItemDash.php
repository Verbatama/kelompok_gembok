<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class MapItemDash extends Model
{
    protected $fillable = [
        'item_type',
        'parent_id',
        'name',
        'latitude',
        'longitude',
        'genieacs_device_id',
        'status',
        'properties',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'properties' => 'array',
    ];

    /**
     * Parent dari item ini.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MapItem::class, 'parent_id');
    }

    /**
     * Anak-anak dari item ini.
     */
    public function children(): HasMany
    {
        return $this->hasMany(MapItem::class, 'parent_id');
    }

    public function outgoingConnections(): HasMany
    {
        return $this->hasMany(MapConnection::class, 'from_item_id');
    }

    /**
     * Semua koneksi menuju item ini.
     */
    public function incomingConnections(): HasMany
    {
        return $this->hasMany(MapConnection::class, 'to_item_id');
    }

    /**
     * Daftar port PON milik server.
     */
    public function ponPorts(): HasMany
    {
        return $this->hasMany(ServerPonPort::class);
    }

    public function oltConfig(): HasOne
    {
        return $this->hasOne(OltConfig::class);
    }

    public function oltPonPorts(): HasMany
    {
        return $this->hasMany(OltPonPort::class, 'olt_item_id');
    }

    public function odcConfig(): HasOne
    {
        return $this->hasOne(OdcConfig::class);
    }

    public function childOdcs(): HasMany
    {
        return $this->hasMany(OdcConfig::class, 'server_id');
    }

    public function odpConfig(): HasOne
    {
        return $this->hasOne(OdpConfig::class);
    }

    public function onuConfig(): HasOne
    {
        return $this->hasOne(OnuConfig::class);
    }
}

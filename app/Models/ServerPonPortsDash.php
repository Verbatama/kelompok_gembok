<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServerPonPortsDash extends Model
{
    protected $fillable = [
        'map_item_id',
        'port_number',
        'output_power',
    ];

    protected $casts = [
        'output_power' => 'decimal:2',
    ];

    /**
     * Server (MapItem) pemilik port PON.
     */
    public function mapItem(): BelongsTo
    {
        return $this->belongsTo(MapItem::class);
    }
}

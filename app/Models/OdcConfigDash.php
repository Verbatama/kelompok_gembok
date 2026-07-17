<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class OdcConfigDash extends Model
{
    protected $table = 'odc_config';

    protected $fillable = [
        'map_item_id',
        'olt_pon_port_id',
        'server_id',
        'server_pon_port',
        'port_count',
        'parent_attenuation_db',
        'calculated_power',
    ];

    protected $casts = [
        'server_pon_port' => 'integer',
        'port_count' => 'integer',
        'parent_attenuation_db' => 'decimal:2',
        'calculated_power' => 'decimal:2',
    ];

    /**
     * ODC pada map_items.
     */
    public function mapItem(): BelongsTo
    {
        return $this->belongsTo(MapItem::class);
    }

    /**
     * PON OLT sumber.
     */
    public function oltPonPort(): BelongsTo
    {
        return $this->belongsTo(OltPonPort::class);
    }

    /**
     * Server induk.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(MapItem::class, 'server_id');
    }
}

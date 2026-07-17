<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class OdpConfigDash extends Model
{
    protected $table = 'odp_config';

    protected $fillable = [
        'map_item_id',
        'odc_port',
        'input_power',
        'parent_odp_port',
        'port_count',
        'use_splitter',
        'use_secondary_splitter',
        'secondary_splitter_ratio',
        'custom_secondary_ratio_output_port',
        'splitter_ratio',
        'custom_ratio_output_port',
        'calculated_power',
        'port_rx_power',
    ];

    protected $casts = [
        'odc_port' => 'integer',
        'port_count' => 'integer',
        'use_splitter' => 'boolean',
        'use_secondary_splitter' => 'boolean',
        'input_power' => 'decimal:2',
        'calculated_power' => 'decimal:2',
        'port_rx_power' => 'array',
    ];

    /**
     * ODP pada map_items.
     */
    public function mapItem(): BelongsTo
    {
        return $this->belongsTo(MapItem::class);
    }
}

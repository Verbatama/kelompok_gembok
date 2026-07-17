<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OltConfigDash extends Model
{
    protected $table = 'olt_config';

    protected $fillable = [
        'map_item_id',
        'output_power',
        'pon_count',
        'attenuation_db',
        'olt_link',
    ];

    protected $casts = [
        'output_power' => 'decimal:2',
        'attenuation_db' => 'decimal:2',
        'pon_count' => 'integer',
    ];

    /**
     * OLT pada map_items.
     */
    public function mapItem(): BelongsTo
    {
        return $this->belongsTo(MapItem::class);
    }
}

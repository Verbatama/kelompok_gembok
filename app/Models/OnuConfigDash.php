<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class OnuConfigDash extends Model
{
    protected $table = 'onu_config';

    protected $fillable = [
        'map_item_id',
        'odp_port',
        'customer_name',
        'genieacs_device_id',
    ];

    protected $casts = [
        'odp_port' => 'integer',
    ];

    /**
     * ONU pada map_items.
     */
    public function mapItem(): BelongsTo
    {
        return $this->belongsTo(MapItem::class);
    }
}

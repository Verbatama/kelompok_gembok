<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class OltPonPortsDash extends Model
{
    protected $fillable = [
        'olt_item_id',
        'pon_number',
        'output_power',
    ];

    protected $casts = [
        'pon_number' => 'integer',
        'output_power' => 'decimal:2',
    ];

    /**
     * OLT pemilik port PON.
     */
    public function olt(): BelongsTo
    {
        return $this->belongsTo(MapItem::class, 'olt_item_id');
    }
}

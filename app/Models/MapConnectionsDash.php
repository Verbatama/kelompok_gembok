<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapConnectionsDash extends Model
{
    protected $fillable = [
        'from_item_id',
        'to_item_id',
        'connection_type',
        'path_coordinates',
    ];

    protected $casts = [
        'path_coordinates' => 'array',
    ];

    public function fromItem(): BelongsTo
    {
        return $this->belongsTo(MapItem::class, 'from_item_id');
    }


    public function toItem(): BelongsTo
    {
        return $this->belongsTo(MapItem::class, 'to_item_id');
    }
}

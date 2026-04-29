<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'channex_id',
        'name',
        'description',
        'capacity',
        'room_type',
        'base_price',
        'currency',
        'amenities',
        'metadata',
    ];

    protected $casts = [
        'amenities' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the property this room belongs to
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'channex_id',
        'name',
        'channel_type',
        'status',
        'api_credentials',
        'sync_enabled',
        'last_synced_at',
        'metadata',
    ];

    protected $casts = [
        'api_credentials' => 'encrypted:array',
        'last_synced_at' => 'datetime',
        'metadata' => 'array',
        'sync_enabled' => 'boolean',
    ];

    /**
     * Get the property this channel belongs to
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Check if channel is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->sync_enabled;
    }

    /**
     * Mark as synced
     */
    public function markAsSynced(): void
    {
        $this->update(['last_synced_at' => now()]);
    }
}

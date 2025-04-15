<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Subscription extends Model
{
    use SoftDeletes, CrudTrait;

    protected $fillable = [
        'user_id',
        'plan_name',
        'price',
        'billing_cycle',
        'request_limit',
        'starts_at',
        'ends_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'request_limit' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the subscription is active.
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();

        if ($now->lessThan($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && $now->greaterThan($this->ends_at)) {
            return false;
        }

        return true;
    }

    /**
     * Scope a query to only include active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            });
    }

    /**
     * Scope a query to only include expired subscriptions.
     */
    public function scopeExpired($query)
    {
        return $query->where('ends_at', '<', now());
    }
}

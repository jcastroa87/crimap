<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class ApiKey extends Model
{
    use SoftDeletes, CrudTrait;

    protected $fillable = [
        'user_id',
        'name',
        'key',
        'description',
        'is_active',
        'rate_limit',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'rate_limit' => 'integer',
    ];

    /**
     * Get the user that owns the API key.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the API key is valid and active.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && now()->greaterThan($this->expires_at)) {
            return false;
        }

        return true;
    }

    /**
     * Generate a new API key.
     */
    public static function generateKey(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Update the last used timestamp.
     */
    public function markAsUsed(): self
    {
        $this->update(['last_used_at' => now()]);
        return $this;
    }
}

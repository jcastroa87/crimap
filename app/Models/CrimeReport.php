<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class CrimeReport extends Model
{
    use SoftDeletes, CrudTrait;

    protected $fillable = [
        'user_id',
        'crime_type_id',
        'latitude',
        'longitude',
        'description',
        'occurred_at',
        'status',
        'media_files',
        'admin_notes',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'occurred_at' => 'datetime',
        'approved_at' => 'datetime',
        'media_files' => 'array',
    ];

    /**
     * Get the user who reported this crime.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the crime type for this report.
     */
    public function crimeType(): BelongsTo
    {
        return $this->belongsTo(CrimeType::class);
    }

    /**
     * Get the admin who approved this report.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope a query to only include approved reports.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include pending reports.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include rejected reports.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}

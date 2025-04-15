<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class CrimeType extends Model
{
    use CrudTrait;
    protected $fillable = [
        'name',
        'icon',
        'description',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the crime reports for this crime type.
     */
    public function crimeReports(): HasMany
    {
        return $this->hasMany(CrimeReport::class);
    }
}

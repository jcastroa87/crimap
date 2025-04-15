<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the crime reports submitted by the user.
     */
    public function crimeReports()
    {
        return $this->hasMany(CrimeReport::class);
    }

    /**
     * Get the approved crime reports by the admin.
     */
    public function approvedReports()
    {
        return $this->hasMany(CrimeReport::class, 'approved_by');
    }

    /**
     * Get the API keys owned by the user.
     */
    public function apiKeys()
    {
        return $this->hasMany(ApiKey::class);
    }

    /**
     * Get the subscriptions owned by the user.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}

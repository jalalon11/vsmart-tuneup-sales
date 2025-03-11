<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'phone',
        'position',
        'address',
        'bio',
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
            'is_admin' => 'boolean',
            'phone' => 'string',
            'position' => 'string',
            'address' => 'string',
            'bio' => 'string',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }
    
    /**
     * Get the user's role display name.
     */
    public function getPositionDisplayAttribute(): string
    {
        return match($this->position) {
            'admin' => 'Administrator',
            'manager' => 'Manager',
            'technician' => 'Technician',
            'sales' => 'Sales Representative',
            default => 'Staff Member'
        };
    }
}

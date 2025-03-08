<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'facebook_url',
        'phone',
        'address',
    ];

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }
} 
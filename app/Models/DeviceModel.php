<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeviceModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model_name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the devices that use this model.
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'model_id');
    }

    /**
     * Get the full name of the device model (brand + model).
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->brand} {$this->model_name}";
    }
}

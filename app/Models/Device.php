<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'brand',
        'model',
        'status',
        'model_id',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function deviceModel(): BelongsTo
    {
        return $this->belongsTo(DeviceModel::class, 'model_id');
    }

    public function repairItems(): HasMany
    {
        return $this->hasMany(RepairItem::class);
    }

    public function repairs(): HasManyThrough
    {
        return $this->hasManyThrough(
            Repair::class,
            RepairItem::class,
            'device_id',  // Foreign key on repair_items table
            'id',         // Local key on repairs table
            'id',         // Local key on devices table
            'repair_id'   // Foreign key on repair_items table
        )->select('repairs.*')->distinct();  // Select all fields from repairs table and ensure distinct results
    }

    /**
     * Get the brand name, either from direct field or from device model.
     */
    public function getBrandNameAttribute(): string
    {
        return $this->deviceModel?->brand ?? $this->brand ?? 'Unknown';
    }

    /**
     * Get the model name, either from direct field or from device model.
     */
    public function getModelNameAttribute(): string
    {
        return $this->deviceModel?->model_name ?? $this->model ?? 'Unknown';
    }
} 
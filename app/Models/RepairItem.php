<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairItem extends Model
{
    protected $fillable = [
        'repair_id',
        'device_id',
        'service_id',
        'cost',
        'notes',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];

    public function repair(): BelongsTo
    {
        return $this->belongsTo(Repair::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
} 
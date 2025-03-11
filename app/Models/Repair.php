<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'notes',
        'started_at',
        'completed_at',
        'payment_method',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Remove the automatic sale creation/deletion since it's handled in the controller
        static::updated(function ($repair) {
            if ($repair->isDirty('status')) {
                $oldStatus = $repair->getOriginal('status');
                $newStatus = $repair->status;

                if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                    // Set completed_at if not set
                    if (!$repair->completed_at) {
                        $repair->completed_at = now();
                        $repair->save();
                    }
                    
                    // Create a single sale record for the entire repair
                    $repair->sales()->create([
                        'amount' => $repair->total_cost,
                        'sale_date' => $repair->completed_at ?? now(),
                    ]);
                } elseif ($oldStatus === 'completed' && $newStatus !== 'completed') {
                    // Clear completed_at date and delete sales
                    $repair->completed_at = null;
                    $repair->sales()->delete();
                    $repair->save();
                }
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(RepairItem::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function getTotalCostAttribute(): float
    {
        return $this->items->sum('cost');
    }

    // Helper methods to get the first device and service
    public function getDeviceAttribute()
    {
        return $this->items->first()?->device;
    }

    public function getServiceAttribute()
    {
        return $this->items->first()?->service;
    }
} 
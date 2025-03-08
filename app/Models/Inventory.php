<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'brand',
        'model',
        'serial_number',
        'quantity',
        'unit_price',
        'selling_price',
        'description',
        'status',
        'reorder_point'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity' => 'integer',
        'reorder_point' => 'integer'
    ];

    public function scopeLowStock($query)
    {
        return $query->where('quantity', '<=', 'reorder_point');
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '=', 0);
    }
} 
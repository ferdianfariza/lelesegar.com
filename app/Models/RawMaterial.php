<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'unit',
        'price_per_unit',
        'beginning_stock',
        'current_stock',
        'minimum_stock',
        'is_active',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'beginning_stock' => 'decimal:2',
        'current_stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function usages()
    {
        return $this->hasMany(RawMaterialUsage::class);
    }

    // Calculate ending stock
    public function getEndingStockAttribute()
    {
        return $this->current_stock;
    }

    // Calculate total value of remaining stock
    public function getStockValueAttribute()
    {
        return $this->current_stock * $this->price_per_unit;
    }
}

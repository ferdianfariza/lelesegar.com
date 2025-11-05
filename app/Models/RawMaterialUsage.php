<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterialUsage extends Model
{
    protected $fillable = [
        'raw_material_id',
        'usage_date',
        'quantity',
        'price_per_unit',
        'total_cost',
        'notes',
    ];

    protected $casts = [
        'usage_date' => 'date',
        'quantity' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    // Auto-calculate total cost before saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($usage) {
            $usage->total_cost = $usage->quantity * $usage->price_per_unit;
        });
    }
}

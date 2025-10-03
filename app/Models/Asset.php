<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'asset_type',
        'purchase_price',
        'current_value',
        'depreciation_rate',
        'purchase_date',
        'useful_life_years',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'current_value' => 'decimal:2',
        'depreciation_rate' => 'decimal:2',
        'purchase_date' => 'date',
    ];
}

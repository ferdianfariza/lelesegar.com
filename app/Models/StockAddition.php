<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAddition extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'addition_date',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'addition_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

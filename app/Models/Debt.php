<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = [
        'name',
        'debt_date',
        'amount',
        'payment_method',
        'description',
        'status',
        'paid_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'debt_date' => 'date',
        'paid_date' => 'date',
    ];
}

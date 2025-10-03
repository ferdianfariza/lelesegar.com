<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    protected $fillable = [
        'name',
        'receivable_date',
        'amount',
        'payment_method',
        'description',
        'status',
        'paid_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'receivable_date' => 'date',
        'paid_date' => 'date',
    ];
}

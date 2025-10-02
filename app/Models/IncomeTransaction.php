<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeTransaction extends Model
{
    protected $fillable = [
        'transaction_code',
        'transaction_date',
        'income_type',
        'amount',
        'description',
        'order_id',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function journalEntries()
    {
        return $this->morphMany(JournalEntry::class, 'reference');
    }
}

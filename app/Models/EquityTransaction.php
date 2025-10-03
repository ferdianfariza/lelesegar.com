<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquityTransaction extends Model
{
    protected $fillable = [
        'transaction_code',
        'transaction_date',
        'equity_type',
        'amount',
        'description',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function journalEntries()
    {
        return $this->morphMany(JournalEntry::class, 'reference');
    }
}

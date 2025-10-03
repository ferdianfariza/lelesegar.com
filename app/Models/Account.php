<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'code',
        'name',
        'account_type',
        'normal_balance',
        'parent_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function journalEntryLines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function getBalance()
    {
        $debitTotal = $this->journalEntryLines()->where('entry_type', 'debit')->sum('amount');
        $creditTotal = $this->journalEntryLines()->where('entry_type', 'credit')->sum('amount');
        
        if ($this->normal_balance === 'debit') {
            return $debitTotal - $creditTotal;
        }
        return $creditTotal - $debitTotal;
    }
}

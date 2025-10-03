<?php

namespace App\Observers;

use App\Models\ExpenseTransaction;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\Account;

class ExpenseTransactionObserver
{
    /**
     * Handle the ExpenseTransaction "created" event.
     */
    public function created(ExpenseTransaction $expenseTransaction): void
    {
        // Create journal entry for expense transaction
        $journalEntry = JournalEntry::create([
            'entry_code' => 'JE-EXP-' . $expenseTransaction->id,
            'entry_date' => $expenseTransaction->transaction_date,
            'description' => $expenseTransaction->description,
            'reference_type' => ExpenseTransaction::class,
            'reference_id' => $expenseTransaction->id,
        ]);

        // Debit: Expense account (increase expense)
        $expenseAccount = Account::where('code', '5-6000')->first(); // Default to operational expenses
        if ($expenseTransaction->expenseCategory->code === 'GAJI') {
            $expenseAccount = Account::where('code', '5-2000')->first();
        } elseif ($expenseTransaction->expenseCategory->code === 'LISTRIK') {
            $expenseAccount = Account::where('code', '5-3000')->first();
        } elseif ($expenseTransaction->expenseCategory->code === 'TELP') {
            $expenseAccount = Account::where('code', '5-4000')->first();
        }
        
        if ($expenseAccount) {
            JournalEntryLine::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $expenseAccount->id,
                'entry_type' => 'debit',
                'amount' => $expenseTransaction->amount,
                'description' => $expenseTransaction->expenseCategory->name,
            ]);
        }

        // Credit: Cash (decrease asset)
        $cashAccount = Account::where('code', '1-1000')->first();
        if ($cashAccount) {
            JournalEntryLine::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $cashAccount->id,
                'entry_type' => 'credit',
                'amount' => $expenseTransaction->amount,
                'description' => 'Cash paid for ' . $expenseTransaction->expenseCategory->name,
            ]);
        }
    }

    public function updated(ExpenseTransaction $expenseTransaction): void
    {
        //
    }

    public function deleted(ExpenseTransaction $expenseTransaction): void
    {
        //
    }

    public function restored(ExpenseTransaction $expenseTransaction): void
    {
        //
    }

    public function forceDeleted(ExpenseTransaction $expenseTransaction): void
    {
        //
    }
}

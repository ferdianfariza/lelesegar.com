<?php

namespace App\Observers;

use App\Models\EquityTransaction;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\Account;

class EquityTransactionObserver
{
    /**
     * Handle the EquityTransaction "created" event.
     */
    public function created(EquityTransaction $equityTransaction): void
    {
        // Create journal entry for equity transaction
        $journalEntry = JournalEntry::create([
            'entry_code' => 'JE-EQ-' . $equityTransaction->id,
            'entry_date' => $equityTransaction->transaction_date,
            'description' => $equityTransaction->description,
            'reference_type' => EquityTransaction::class,
            'reference_id' => $equityTransaction->id,
        ]);

        if ($equityTransaction->equity_type === 'owner_withdrawal') {
            // Owner withdrawal: Debit Prive, Credit Cash
            $priveAccount = Account::where('code', '3-2000')->first();
            if ($priveAccount) {
                JournalEntryLine::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $priveAccount->id,
                    'entry_type' => 'debit',
                    'amount' => $equityTransaction->amount,
                    'description' => 'Owner withdrawal',
                ]);
            }

            $cashAccount = Account::where('code', '1-1000')->first();
            if ($cashAccount) {
                JournalEntryLine::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $cashAccount->id,
                    'entry_type' => 'credit',
                    'amount' => $equityTransaction->amount,
                    'description' => 'Cash withdrawn by owner',
                ]);
            }
        } else {
            // Capital contribution: Debit Cash, Credit Capital
            $cashAccount = Account::where('code', '1-1000')->first();
            if ($cashAccount) {
                JournalEntryLine::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $cashAccount->id,
                    'entry_type' => 'debit',
                    'amount' => $equityTransaction->amount,
                    'description' => 'Cash received as capital',
                ]);
            }

            $capitalAccount = Account::where('code', '3-1000')->first();
            if ($capitalAccount) {
                JournalEntryLine::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $capitalAccount->id,
                    'entry_type' => 'credit',
                    'amount' => $equityTransaction->amount,
                    'description' => 'Capital contribution',
                ]);
            }
        }
    }

    public function updated(EquityTransaction $equityTransaction): void
    {
        //
    }

    public function deleted(EquityTransaction $equityTransaction): void
    {
        //
    }

    public function restored(EquityTransaction $equityTransaction): void
    {
        //
    }

    public function forceDeleted(EquityTransaction $equityTransaction): void
    {
        //
    }
}

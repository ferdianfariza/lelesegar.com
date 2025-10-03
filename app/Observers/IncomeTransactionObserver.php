<?php

namespace App\Observers;

use App\Models\IncomeTransaction;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\Account;

class IncomeTransactionObserver
{
    /**
     * Handle the IncomeTransaction "created" event.
     */
    public function created(IncomeTransaction $incomeTransaction): void
    {
        // Reduce inventory if this is a sales transaction with product
        if ($incomeTransaction->income_type === 'sales' && $incomeTransaction->product_id && $incomeTransaction->quantity) {
            $inventory = \App\Models\Inventory::where('product_id', $incomeTransaction->product_id)->first();
            if ($inventory) {
                $quantityBefore = $inventory->quantity;
                $inventory->quantity -= $incomeTransaction->quantity;
                $inventory->save();

                // Record inventory movement
                \App\Models\InventoryMovement::create([
                    'product_id' => $incomeTransaction->product_id,
                    'movement_type' => 'out',
                    'quantity' => $incomeTransaction->quantity,
                    'quantity_before' => $quantityBefore,
                    'quantity_after' => $inventory->quantity,
                    'reference_type' => get_class($incomeTransaction),
                    'reference_id' => $incomeTransaction->id,
                    'notes' => 'Penjualan: ' . $incomeTransaction->description,
                ]);
            }
        }

        // Create journal entry for income transaction
        $journalEntry = JournalEntry::create([
            'entry_code' => 'JE-INC-' . $incomeTransaction->id,
            'entry_date' => $incomeTransaction->transaction_date,
            'description' => $incomeTransaction->description,
            'reference_type' => IncomeTransaction::class,
            'reference_id' => $incomeTransaction->id,
        ]);

        // Debit: Cash (increase asset)
        $cashAccount = Account::where('code', '1-1000')->first();
        if ($cashAccount) {
            JournalEntryLine::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $cashAccount->id,
                'entry_type' => 'debit',
                'amount' => $incomeTransaction->amount,
                'description' => 'Cash received from ' . $incomeTransaction->income_type,
            ]);
        }

        // Credit: Revenue or Capital account
        if ($incomeTransaction->income_type === 'sales') {
            $revenueAccount = Account::where('code', '4-1000')->first();
            if ($revenueAccount) {
                JournalEntryLine::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $revenueAccount->id,
                    'entry_type' => 'credit',
                    'amount' => $incomeTransaction->amount,
                    'description' => 'Sales revenue',
                ]);
            }
        } elseif ($incomeTransaction->income_type === 'capital') {
            $capitalAccount = Account::where('code', '3-1000')->first();
            if ($capitalAccount) {
                JournalEntryLine::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $capitalAccount->id,
                    'entry_type' => 'credit',
                    'amount' => $incomeTransaction->amount,
                    'description' => 'Capital contribution',
                ]);
            }
        } else {
            $otherRevenueAccount = Account::where('code', '4-2000')->first();
            if ($otherRevenueAccount) {
                JournalEntryLine::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $otherRevenueAccount->id,
                    'entry_type' => 'credit',
                    'amount' => $incomeTransaction->amount,
                    'description' => 'Other revenue',
                ]);
            }
        }
    }

    public function updated(IncomeTransaction $incomeTransaction): void
    {
        //
    }

    public function deleted(IncomeTransaction $incomeTransaction): void
    {
        //
    }

    public function restored(IncomeTransaction $incomeTransaction): void
    {
        //
    }

    public function forceDeleted(IncomeTransaction $incomeTransaction): void
    {
        //
    }
}

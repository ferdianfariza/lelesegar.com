<?php

namespace App\Observers;

use App\Models\StockAddition;
use App\Models\Inventory;
use App\Models\InventoryMovement;

class StockAdditionObserver
{
    public function created(StockAddition $stockAddition): void
    {
        // Update inventory
        $inventory = Inventory::where('product_id', $stockAddition->product_id)->first();
        if ($inventory) {
            $quantityBefore = $inventory->quantity;
            $inventory->quantity += $stockAddition->quantity;
            $inventory->save();

            // Record inventory movement
            InventoryMovement::create([
                'product_id' => $stockAddition->product_id,
                'movement_type' => 'in',
                'quantity' => $stockAddition->quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $inventory->quantity,
                'reference_type' => get_class($stockAddition),
                'reference_id' => $stockAddition->id,
                'notes' => 'Penambahan bahan baku: ' . ($stockAddition->notes ?? ''),
            ]);
        }
    }

    public function updated(StockAddition $stockAddition): void
    {
        //
    }

    public function deleted(StockAddition $stockAddition): void
    {
        //
    }
}

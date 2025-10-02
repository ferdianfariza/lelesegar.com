<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Inventory;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        // Automatically create inventory record for new product
        Inventory::create([
            'product_id' => $product->id,
            'quantity' => 0,
            'minimum_stock' => 0,
            'unit' => $product->unit,
            'notes' => 'Initial inventory created automatically',
        ]);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Update inventory unit if product unit changes
        if ($product->isDirty('unit') && $product->inventory) {
            $product->inventory->update(['unit' => $product->unit]);
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}

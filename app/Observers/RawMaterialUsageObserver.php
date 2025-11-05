<?php

namespace App\Observers;

use App\Models\RawMaterialUsage;

class RawMaterialUsageObserver
{
    /**
     * Handle the RawMaterialUsage "creating" event.
     */
    public function creating(RawMaterialUsage $rawMaterialUsage): void
    {
        // Calculate total cost before saving
        $rawMaterialUsage->total_cost = $rawMaterialUsage->quantity * $rawMaterialUsage->price_per_unit;
    }

    /**
     * Handle the RawMaterialUsage "created" event.
     */
    public function created(RawMaterialUsage $rawMaterialUsage): void
    {
        // Validate and reduce raw material stock when usage is recorded
        $rawMaterial = $rawMaterialUsage->rawMaterial;
        
        // Check if there's sufficient stock
        if ($rawMaterial->current_stock < $rawMaterialUsage->quantity) {
            throw new \Exception("Stok tidak cukup! Stok tersedia: {$rawMaterial->current_stock} {$rawMaterial->unit}, dibutuhkan: {$rawMaterialUsage->quantity} {$rawMaterial->unit}");
        }
        
        $rawMaterial->current_stock -= $rawMaterialUsage->quantity;
        $rawMaterial->save();
    }

    /**
     * Handle the RawMaterialUsage "updating" event.
     */
    public function updating(RawMaterialUsage $rawMaterialUsage): void
    {
        // Recalculate total cost if quantity or price changed
        if ($rawMaterialUsage->isDirty('quantity') || $rawMaterialUsage->isDirty('price_per_unit')) {
            $rawMaterialUsage->total_cost = $rawMaterialUsage->quantity * $rawMaterialUsage->price_per_unit;
        }
    }

    /**
     * Handle the RawMaterialUsage "updated" event.
     */
    public function updated(RawMaterialUsage $rawMaterialUsage): void
    {
        // Adjust stock if quantity changed
        if ($rawMaterialUsage->wasChanged('quantity')) {
            $rawMaterial = $rawMaterialUsage->rawMaterial;
            $oldQuantity = $rawMaterialUsage->getOriginal('quantity');
            $newQuantity = $rawMaterialUsage->quantity;
            $difference = $newQuantity - $oldQuantity;
            
            // Check if there's sufficient stock for the increase
            if ($difference > 0 && $rawMaterial->current_stock < $difference) {
                throw new \Exception("Stok tidak cukup! Stok tersedia: {$rawMaterial->current_stock} {$rawMaterial->unit}, dibutuhkan tambahan: {$difference} {$rawMaterial->unit}");
            }
            
            $rawMaterial->current_stock -= $difference;
            $rawMaterial->save();
        }
    }

    /**
     * Handle the RawMaterialUsage "deleted" event.
     */
    public function deleted(RawMaterialUsage $rawMaterialUsage): void
    {
        // Return stock to raw material when usage is deleted
        $rawMaterial = $rawMaterialUsage->rawMaterial;
        $rawMaterial->current_stock += $rawMaterialUsage->quantity;
        $rawMaterial->save();
    }

    /**
     * Handle the RawMaterialUsage "restored" event.
     */
    public function restored(RawMaterialUsage $rawMaterialUsage): void
    {
        //
    }

    /**
     * Handle the RawMaterialUsage "force deleted" event.
     */
    public function forceDeleted(RawMaterialUsage $rawMaterialUsage): void
    {
        //
    }
}

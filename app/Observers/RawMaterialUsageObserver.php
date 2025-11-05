<?php

namespace App\Observers;

use App\Models\RawMaterialUsage;

class RawMaterialUsageObserver
{
    /**
     * Handle the RawMaterialUsage "created" event.
     */
    public function created(RawMaterialUsage $rawMaterialUsage): void
    {
        // Reduce raw material stock when usage is recorded
        $rawMaterial = $rawMaterialUsage->rawMaterial;
        $rawMaterial->current_stock -= $rawMaterialUsage->quantity;
        $rawMaterial->save();
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

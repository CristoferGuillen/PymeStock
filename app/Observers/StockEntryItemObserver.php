<?php

namespace App\Observers;

use App\Models\StockEntryItem;

class StockEntryItemObserver
{
    public function creating(StockEntryItem $item): void
    {
        $product = $item->product;
        $item->prev_unit_cost = $product->unit_cost;
        $item->prev_status = $product->status;
        $item->subtotal = $item->quantity * $item->unit_cost;
    }


    public function created(StockEntryItem $item): void
    {
    $product = $item->product;
    
    $product->update([
        'units_available' => $product->units_available + $item->quantity,
        'unit_cost' => $item->unit_cost,
        'status' => 'pending_pricing',
    ]);
    
    $this->recalculateStockEntryTotal($item->stockEntry);
    }


    public function updating(StockEntryItem $item): void
    {
        $item->subtotal = $item->quantity * $item->unit_cost;
    }


    public function updated(StockEntryItem $item): void
    {
        $oldQuantity = $item->getOriginal('quantity');
        $newQuantity = $item->quantity;
        $difference = $newQuantity - $oldQuantity;

        if ($difference > 0) {
            $item->product->increment('units_available', $difference);
        } elseif ($difference < 0) {
            $item->product->decrement('units_available', abs($difference));
        }

    if ($item->isDirty('unit_cost')) {
            $item->product->update([
                'unit_cost' => $item->unit_cost,
                'status' => 'pending_pricing',
            ]);
        }

        $this->recalculateStockEntryTotal($item->stockEntry);
    }


    public function deleted(StockEntryItem $item): void
    {

        $product = $item->product;
        $product->descrement('units_available', $item->quantity);

        $product->update([
            'unit_cost' => $item->prev_unit_cost ?? $product->unit_cost,
            'status' => $item->prev_status ?? $product->status,
        ]);

        $this->recalculateStockEntryTotal($item->stockEntry);
    }


    private function recalculateStockEntryTotal($stockEntry): void
    {
        $total = $stockEntry->items()->sum('subtotal');
        $stockEntry->update(['total_cost' => $total]);
    }
}

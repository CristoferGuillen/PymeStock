<?php

namespace App\Observers;

use App\Models\StockEntryItem;

class StockEntryItemObserver
{
    public function creating(StockEntryItem $item): void
    {
        $item->subtotal = $item->quantity * $item->unit_cost;
    }


    public function created(StockEntryItem $item): void
    {
        $item->product->increment('units_available', $item->quantity);
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

        $this->recalculateStockEntryTotal($item->stockEntry);
    }


    public function deleted(StockEntryItem $item): void
    {
        $item->product->decrement('units_available', $item->quantity);
        $this->recalculateStockEntryTotal($item->stockEntry);
    }


    private function recalculateStockEntryTotal($stockEntry): void
    {
        $total = $stockEntry->items()->sum('subtotal');
        $stockEntry->update(['total_cost' => $total]);
    }
}

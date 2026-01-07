<?php

namespace App\Observers;

use App\Models\StockEntry;

class StockEntryObserver
{

    public function creating(StockEntry $stockEntry): void
    {
        if (empty($stockEntry->entry_number)) {
            $stockEntry->entry_number = $this->generateEntryNumber();
        }
    }


        public function deleting(StockEntry $stockEntry): void
    {

        foreach ($stockEntry->items as $item) {
            $product = $item->product;
            
            $product->update([
                'units_available' => $product->units_available - $item->quantity,
                'unit_cost' => $item->prev_unit_cost ?? $product->unit_cost,
                'status' => $item->prev_status ?? $product->status,
            ]);
        }
        
        $stockEntry->items()->delete();
    }


    private function generateEntryNumber(): string
    {
        $lastEntry = StockEntry::withTrashed()
            ->orderBy('entry_number', 'desc')
            ->first();
        
            if ($lastEntry && $lastEntry->entry_number) {
            $lastNumber = (int) substr($lastEntry->entry_number, 4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'ING-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}

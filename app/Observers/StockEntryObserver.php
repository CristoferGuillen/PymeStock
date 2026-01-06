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


    private function generateEntryNumber(): string
    {
        $lastId = StockEntry::max('id') ?? 0;
        
        $nextNumber = $lastId + 1;
        
        return 'ING-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}

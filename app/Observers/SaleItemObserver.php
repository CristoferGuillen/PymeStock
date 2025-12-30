<?php

namespace App\Observers;

use App\Models\SaleItem;
use Exception;

class SaleItemObserver
{
    public function creating(SaleItem $item): void
    {
        $item->subtotal = $item->quantity * $item->unit_price;
    }

    public function updating(SaleItem $item): void
    {
        $item->subtotal = $item->quantity * $item->unit_price;
    }

    public function created(SaleItem $item): void
    {
        $this->updateStock($item, 'decrease');
        
        $item->sale->touch();
    }

    public function updated(SaleItem $item): void
    {
        if ($item->isDirty('quantity')) {
            $oldQuantity = $item->getOriginal('quantity');
            $difference = $item->quantity - $oldQuantity;
            
            if ($difference > 0) {
                $item->product->reduceStock($difference);
            } else {
                $item->product->increment('units_available', abs($difference));
            }
        }
        
        $item->sale->touch();
    }

    public function deleting(SaleItem $item): void
    {
        $this->updateStock($item, 'increase');
    }

    public function deleted(SaleItem $item): void
    {
        $item->sale->touch();
    }

    private function updateStock(SaleItem $item, string $action): void
    {
        try {
            if ($action === 'decrease') {
                $item->product->reduceStock($item->quantity);
            } else {
                $item->product->increment('units_available', $item->quantity);
            }
        } catch (Exception $e) {
            throw new Exception("Error al actualizar stock: " . $e->getMessage());
        }
    }
}
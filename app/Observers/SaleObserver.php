<?php

namespace App\Observers;

use App\Models\Sale;

class SaleObserver
{
    public function updating(Sale $sale): void
    {
        if (!$sale->isDirty(['subtotal', 'total'])) {
            $this->calculateTotals($sale);
        }
    }

    private function calculateTotals(Sale $sale): void
    {
        $subtotal = $sale->items()->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $discount = $sale->discount ?? 0;
        $total = $subtotal - $discount;

        $sale->subtotal = $subtotal;
        $sale->total = $total;
    }
}

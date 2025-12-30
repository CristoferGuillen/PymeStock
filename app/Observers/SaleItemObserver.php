<?php

namespace App\Observers;

use App\Models\SaleItem;
use Exception;

class SaleItemObserver
{
    public function creating(SaleItem $item): void
    {
        // Calcular subtotal automáticamente
        $item->subtotal = $item->quantity * $item->unit_price;
    }

    public function updating(SaleItem $item): void
    {
        // Recalcular subtotal al actualizar
        $item->subtotal = $item->quantity * $item->unit_price;
    }

    public function created(SaleItem $item): void
    {
        // Descontar del stock cuando se crea el item
        $this->updateStock($item, 'decrease');
        
        // Actualizar totales de la venta
        $item->sale->touch();
    }

    public function updated(SaleItem $item): void
    {
        // Si cambió la cantidad, ajustar stock
        if ($item->isDirty('quantity')) {
            $oldQuantity = $item->getOriginal('quantity');
            $difference = $item->quantity - $oldQuantity;
            
            if ($difference > 0) {
                // Aumentó la cantidad, descontar más stock
                $item->product->reduceStock($difference);
            } else {
                // Disminuyó la cantidad, devolver stock
                $item->product->increment('units_available', abs($difference));
            }
        }
        
        // Actualizar totales de la venta
        $item->sale->touch();
    }

    public function deleting(SaleItem $item): void
    {
        // Devolver stock cuando se elimina el item
        $this->updateStock($item, 'increase');
    }

    public function deleted(SaleItem $item): void
    {
        // Actualizar totales de la venta
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
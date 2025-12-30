<?php

namespace App\Observers;

use App\Models\Sale;

class SaleObserver
{

    public function creating(Sale $sale): void
    {
        // Generar número de factura automáticamente solo si no existe
        if (empty($sale->invoice_number)) {
            $sale->invoice_number = $this->generateInvoiceNumber();
        }

        // Establecer fecha de venta si no existe
        if (empty($sale->sale_date)) {
            $sale->sale_date = now();
        }
    }

    /**
     * Handle the Sale "created" event.
     */
    public function created(Sale $sale): void
    {
        // Calcular totales después de crear los items
        $this->calculateTotals($sale);
    }


    public function updated(Sale $sale): void
    {
        // Recalcular totales cuando se actualiza la venta
        // Pero solo si no estamos en medio de una actualización silenciosa
        if (!$sale->wasChanged(['subtotal', 'total'])) {
            $this->calculateTotals($sale);
        }
    }


    public function deleting(Sale $sale): void
    {
        // Los items se eliminarán en cascada según la migración
        // y el SaleItemObserver devolverá el stock
    }

    /**
     * Calcular los totales de la venta basándose en sus items
     */
    private function calculateTotals(Sale $sale): void
    {
        // Cargar los items si no están cargados
        $sale->loadMissing('saleItems');
        
        // Calcular subtotal sumando todos los items
        $subtotal = $sale->saleItems->sum('subtotal');
        
        // Aquí puedes agregar lógica adicional:
        // - Descuentos
        // - Impuestos
        // - Cargos adicionales
        
        $total = $subtotal;
        
        // Actualizar sin disparar eventos adicionales (evitar loop infinito)
        $sale->updateQuietly([
            'subtotal' => $subtotal,
            'total' => $total,
        ]);
    }

    /**
     * Generar un número de factura único
     */
    private function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $count = Sale::whereYear('created_at', $year)->count() + 1;
        
        return 'INV-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
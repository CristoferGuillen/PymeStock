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

        if (!$sale->wasChanged(['subtotal', 'total'])) {
            $this->calculateTotals($sale);
        }
    }


    public function deleting(Sale $sale): void
    {

    }


    private function calculateTotals(Sale $sale): void
    {
        $sale->loadMissing('saleItems');
        
        $subtotal = $sale->saleItems->sum('subtotal');
        

        
        $total = $subtotal;
        
        $sale->updateQuietly([
            'subtotal' => $subtotal,
            'total' => $total,
        ]);
    }


    private function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $count = Sale::whereYear('created_at', $year)->count() + 1;
        
        return 'INV-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
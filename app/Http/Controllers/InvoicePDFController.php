<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class InvoicePDFController extends Controller
{
    public function downloadInvoice(Sale $sale): Response
    {
        $data = [
            'sale' => $sale->load('items.product'),
            'items' => $sale->items,
            'businessName' => 'PymeStock',
            'businessAddress' => 'Tu dirección aquí',
            'businessPhone' => '+57 300 123 4567',
            'businessEmail' => 'contacto@pymestock.com',
        ];

        $pdf = Pdf::loadView('invoices.invoice', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('factura-' . $sale->invoice_number . '.pdf');
    }
}

<?php

use App\Http\Controllers\InvoicePDFController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/invoices/{sale}/pdf', [InvoicePDFController::class, 'downloadInvoice'])
        ->name('invoices.download');
});

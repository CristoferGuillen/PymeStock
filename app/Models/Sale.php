<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_name',
        'customer_email',
        'sale_date',
        'subtotal',
        'total',
        'discount',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'discount' => 'decimal:2',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (empty($sale->invoice_number)) {
                $sale->invoice_number = self::generateInvoiceNumber();
            }
        });
    }

  
    private static function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        
        $count = self::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->count() + 1;
        
        return sprintf('INV-%s-%s-%04d', $year, $month, $count);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }


    public function updateTotals(): void
    {
        $subtotal = $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $discount = $this->discount ?? 0;
        $total = $subtotal - $discount;

        $this->updateQuietly([
            'subtotal' => $subtotal,
            'total' => $total,
        ]);
    }
}

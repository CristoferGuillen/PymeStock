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
        'subtotal',
        'total',
        'sale_date'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'sale_date' => 'datetime'
    ];

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
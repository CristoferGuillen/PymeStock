<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'units_available',
        'unit_cost',
        'sales_price',
        'attachment',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'sales_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

        public function stockEntryItems(): HasMany
    {
        return $this->hasMany(StockEntryItem::class);
    }

    public function reduceStock(int $quantity): void
    {
        if ($this->units_available >= $quantity) {
            $this->decrement('units_available', $quantity);
        } else {
            throw new \Exception("Stock insuficiente para {$this->name}. Disponible: {$this->units_available}");
        }
    }

    public function hasStock(int $quantity): bool
    {
        return $this->units_available >= $quantity;
    }

    public function increaseStock(int $quantity): void
    {
        $this->increment('units_available', $quantity);
    }
}
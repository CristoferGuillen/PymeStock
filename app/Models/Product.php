<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category',
        'units_available',
        'unit_cost',
        'sales_price',
        'attachment',
    ];

    public $casts = [
        'unit_cost' => 'decimal:2',
        'sales_price' => 'decimal:2',
    ];


        public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
     public function reduceStock(int $quantity): void
    {
        if ($this->units_available >= $quantity) {
            $this->units_available -= $quantity;
            $this->save();
        } else {
            throw new \Exception("Stock insuficiente para {$this->name}. Disponible: {$this->units_available}");
        }
    }

}

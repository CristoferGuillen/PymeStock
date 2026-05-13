<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'units_available',
        'unit_cost',
        'sales_price',
        'attachment',
        'status',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'units_available' => 'integer',
        'unit_cost' => 'decimal:2',
        'sales_price' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
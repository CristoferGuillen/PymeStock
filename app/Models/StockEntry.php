<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'entry_number',
        'user_id',
        'entry_date',
        'notes',
        'total_cost',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'total_cost' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(StockEntryItem::class);
    }
}

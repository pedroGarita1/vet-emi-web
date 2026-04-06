<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'inventory_item_id',
        'product_name',
        'quantity',
        'unit_price',
        'total',
        'customer_name',
        'sold_at',
    ];

    protected function casts(): array
    {
        return [
            'sold_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}

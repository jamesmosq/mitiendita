<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    // Un item pertenece a una orden
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Un item pertenece a un producto
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
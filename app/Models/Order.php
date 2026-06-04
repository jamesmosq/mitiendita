<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total',
        'notes'
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // Una orden pertenece a un usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Una orden tiene muchos items
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Una orden tiene muchos productos (via order_items)
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'unit_price', 'subtotal')
                    ->withTimestamps();
    }
}
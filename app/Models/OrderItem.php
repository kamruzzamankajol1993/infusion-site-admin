<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',   // Stored as snapshot
        'variation_name', // Stored as snapshot (e.g., "500g")
        'price',          // Unit price at time of purchase
        'quantity',
        'total',          // price * quantity
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Relationship: Item belongs to an Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship: Item belongs to a Product (optional, if product wasn't deleted).
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
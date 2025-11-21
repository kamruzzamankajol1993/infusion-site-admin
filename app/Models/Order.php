<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'user_id',
        // Customer Details
        'first_name',
        'last_name',
        'email',
        'phone',
        'shipping_address',
        'city',
        'zip_code',
        // Financials
        'sub_total',
        'tax',
        'shipping_cost',
        'discount',
        'grand_total',
        // Statuses
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sub_total' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    /**
     * Boot function to handle auto-generation of Order Numbers.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // Auto-generate unique Order # if not provided
            if (empty($order->order_number)) {
                $year = date('Y');
                // Get the last order ID to increment
                $lastOrder = static::orderBy('id', 'desc')->first();
                $sequence = $lastOrder ? $lastOrder->id + 1 : 1;
                
                // Format: ORD-2025-0001
                $order->order_number = 'ORD-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Relationship: Order has many items.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relationship: Order belongs to a User (optional, if logged in).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
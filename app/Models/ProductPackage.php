<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPackage extends Model
{
    use HasFactory;

    protected $table = 'product_packages';

    protected $fillable = [
        'product_id',
        'variation_name',
        'additional_price',
    ];

    protected $casts = [
        'additional_price' => 'decimal:2',
    ];

    /**
     * Get the product that owns the package.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
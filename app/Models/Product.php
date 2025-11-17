<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'image', 'sku', 'stock_quantity',
        'buying_price', 'selling_price', 'discount_price', 
        'is_top_selling_product', 'status', 'order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_top_selling_product' => 'boolean',
        'stock_quantity' => 'integer',
        'buying_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = static::createUniqueSlug($product->name);
            $product->order = ($product::max('order') ?? 0) + 1;
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = static::createUniqueSlug($product->name, $product->id);
            }
        });
    }

    private static function createUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        $query = static::where('slug', $slug);
        if ($id) {
            $query->where('id', '!=', $id);
        }
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            $query = static::where('slug', $slug);
            if ($id) {
                $query->where('id', '!=', $id);
            }
        }
        return $slug;
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the packages for the product.
     */
    public function packages()
    {
        return $this->hasMany(ProductPackage::class);
    }
}
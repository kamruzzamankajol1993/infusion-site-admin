<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookPricingPackage extends Model
{
    use HasFactory;
    protected $table = 'facebook_pricing_packages';
    protected $fillable = ['title', 'price', 'features', 'button_text', 'button_link', 'order'];

    protected $casts = [
        'features' => 'array', // Automatically cast features to/from JSON
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->order = ($model::max('order') ?? 0) + 1;
        });
    }
}
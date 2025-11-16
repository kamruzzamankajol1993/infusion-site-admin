<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FacebookAdsPricingPackage extends Model {
    use HasFactory;
    protected $table = 'facebook_ads_pricing_packages';
    protected $fillable = [
        'category_id', 'title', 'price', 'price_suffix', 
        'features', 'button_text', 'button_link', 'order'
    ];
    protected $casts = [ 'features' => 'array' ];
    protected static function boot(): void {
        parent::boot();
        static::creating(fn($model) => $model->order = ($model::max('order') ?? 0) + 1);
    }
    public function category() {
        return $this->belongsTo(FacebookAdsPricingCategory::class, 'category_id');
    }
}
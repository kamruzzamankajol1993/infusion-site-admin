<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class FacebookAdsPricingCategory extends Model {
    use HasFactory;
    protected $table = 'facebook_ads_pricing_categories';
    protected $fillable = ['name', 'slug', 'order'];
    protected static function boot(): void {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
            $model->order = ($model::max('order') ?? 0) + 1;
        });
        static::updating(fn($model) => $model->slug = Str::slug($model->name));
    }
    public function packages() {
        return $this->hasMany(FacebookAdsPricingPackage::class, 'category_id');
    }
}
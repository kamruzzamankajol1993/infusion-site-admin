<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class VpsPackage extends Model {
    use HasFactory;
    protected $table = 'vps_packages';
    protected $fillable = [
        'category_id', 'title', 'price', 'price_subtitle', 'features', 
        'button_text', 'button_link', 'is_stocked_out', 'order'
    ];
    protected $casts = [
        'features' => 'array',
        'is_stocked_out' => 'boolean',
    ];
    protected static function boot(): void {
        parent::boot();
        static::creating(fn($model) => $model->order = ($model::max('order') ?? 0) + 1);
    }
    public function category() {
        return $this->belongsTo(VpsPackageCategory::class, 'category_id');
    }
}
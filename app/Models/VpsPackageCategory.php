<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class VpsPackageCategory extends Model {
    use HasFactory;
    protected $table = 'vps_package_categories';
    protected $fillable = ['name', 'order'];
    protected static function boot(): void {
        parent::boot();
        static::creating(fn($model) => $model->order = ($model::max('order') ?? 0) + 1);
    }
    public function packages() {
        return $this->hasMany(VpsPackage::class, 'category_id');
    }
}
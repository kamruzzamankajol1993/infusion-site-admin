<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class WebSolutionWorkCategory extends Model {
    use HasFactory;
    protected $table = 'web_solution_work_categories';
    protected $fillable = ['name', 'slug', 'order'];
    protected static function boot(): void {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
            $model->order = ($model::max('order') ?? 0) + 1;
        });
        static::updating(fn($model) => $model->slug = Str::slug($model->name));
    }
    public function items() {
        return $this->hasMany(WebSolutionWorkItem::class, 'category_id');
    }
}
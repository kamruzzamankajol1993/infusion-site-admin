<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WebSolutionWorkItem extends Model {
    use HasFactory;
    protected $table = 'web_solution_work_items';
    protected $fillable = ['category_id', 'image', 'visit_link', 'order'];
    protected static function boot(): void {
        parent::boot();
        static::creating(fn($model) => $model->order = ($model::max('order') ?? 0) + 1);
    }
    public function category() {
        return $this->belongsTo(WebSolutionWorkCategory::class, 'category_id');
    }
}
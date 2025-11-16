<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WebSolutionInclude extends Model {
    use HasFactory;
    protected $table = 'web_solution_includes';
    protected $fillable = ['icon_name', 'title', 'description', 'order'];
    protected static function boot(): void {
        parent::boot();
        static::creating(fn($model) => $model->order = ($model::max('order') ?? 0) + 1);
    }
}
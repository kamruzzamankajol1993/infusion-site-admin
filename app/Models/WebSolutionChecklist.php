<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WebSolutionChecklist extends Model {
    use HasFactory;
    protected $table = 'web_solution_checklists';
    protected $fillable = ['title', 'order'];
    protected static function boot(): void {
        parent::boot();
        static::creating(fn($model) => $model->order = ($model::max('order') ?? 0) + 1);
    }
}
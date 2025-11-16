<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WebSolutionProviding extends Model {
    use HasFactory;
    protected $table = 'web_solution_providings';
    protected $fillable = ['image', 'title', 'button_text', 'button_link', 'order'];
    protected static function boot(): void {
        parent::boot();
        static::creating(fn($model) => $model->order = ($model::max('order') ?? 0) + 1);
    }
}
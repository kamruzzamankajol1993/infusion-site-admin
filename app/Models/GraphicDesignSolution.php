<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GraphicDesignSolution extends Model
{
    use HasFactory;
    protected $table = 'graphic_design_solutions';
    protected $fillable = ['icon', 'title', 'description', 'order'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->order = ($model::max('order') ?? 0) + 1;
        });
    }
}
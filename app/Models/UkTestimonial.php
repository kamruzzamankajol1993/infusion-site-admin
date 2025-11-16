<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class UkTestimonial extends Model {
    use HasFactory;
    protected $table = 'uk_testimonials';
    protected $fillable = ['name', 'designation', 'quote', 'image', 'rating', 'order'];
    protected static function boot(): void {
        parent::boot();
        static::creating(fn($model) => $model->order = ($model::max('order') ?? 0) + 1);
    }
}
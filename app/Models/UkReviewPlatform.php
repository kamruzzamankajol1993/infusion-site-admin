<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class UkReviewPlatform extends Model {
    use HasFactory;
    protected $table = 'uk_review_platforms';
    protected $fillable = ['name', 'image', 'rating_text', 'review_link', 'order'];
    protected static function boot(): void {
        parent::boot();
        static::creating(fn($model) => $model->order = ($model::max('order') ?? 0) + 1);
    }
}
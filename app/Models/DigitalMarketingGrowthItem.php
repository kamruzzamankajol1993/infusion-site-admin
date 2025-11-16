<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalMarketingGrowthItem extends Model
{
    use HasFactory;
    protected $table = 'digital_marketing_growth_items';
    protected $fillable = ['title', 'order'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->order = ($model::max('order') ?? 0) + 1;
        });
    }
}
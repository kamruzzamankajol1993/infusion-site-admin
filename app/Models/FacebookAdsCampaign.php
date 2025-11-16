<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FacebookAdsCampaign extends Model {
    use HasFactory;
    protected $table = 'facebook_ads_campaigns';
    protected $fillable = ['title', 'description', 'order'];
    protected static function boot(): void {
        parent::boot();
        static::creating(fn($model) => $model->order = ($model::max('order') ?? 0) + 1);
    }
}
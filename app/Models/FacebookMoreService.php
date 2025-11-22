<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookMoreService extends Model
{
    use HasFactory;
    protected $table = 'facebook_more_services';
    protected $fillable = ['image', 'title', 'description', 'link_text', 'link_url', 'order'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->order = ($model::max('order') ?? 0) + 1;
        });
    }
}
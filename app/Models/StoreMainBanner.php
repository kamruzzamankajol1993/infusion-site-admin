<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreMainBanner extends Model
{
    use HasFactory;

    protected $table = 'store_main_banners';

    protected $fillable = [
        'image',
        'link',
        'status',
        'order',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->order = ($model::max('order') ?? 0) + 1;
        });
    }
}
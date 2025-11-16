<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyChooseUs extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'why_choose_us';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'image',
        'order',
    ];

    /**
     * The "booted" method of the model.
     * Automatically sets the order for new items.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            // Set the order to be the next number in sequence
            $model->order = ($model::max('order') ?? 0) + 1;
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'subtitle',
        'short_description',
        'image',
        'display_order',
    ];

    /**
     * Accessor for the image URL.
     * Adjust path based on ImageUploadTrait.
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        // Assuming ImageUploadTrait saves relative to 'public/uploads/'
        return $this->image ? asset($this->image) : null;
        // Or if saved relative to public base path:
        // return $this->image ? asset($this->image) : null;
    }
}
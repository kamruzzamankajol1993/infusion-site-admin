<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PressRelease extends Model
{
    use HasFactory;

    protected $table = 'press_releases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug', // <-- ADDED
        'image',
        'release_date',
        // 'type', // <-- REMOVED
        'link',
        'description',
    ];

    protected $casts = [
        'release_date' => 'date:Y-m-d',
    ];

    /**
     * Get the URL for the press release image.
     * Accessor: $pressRelease->image_url
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        // Adjust path based on your ImageUploadTrait implementation
        return $this->image ? asset('uploads/' . $this->image) : null;
        // Or: return $this->image ? asset($this->image) : null;
    }
}

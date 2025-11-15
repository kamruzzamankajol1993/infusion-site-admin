<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'start_date',
        'end_date',
        'time',
        'description',
        'status',
        'image',
    ];

    /**
     * Get the URL for the event image.
     * Accessor: $event->image_url
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        // Assuming ImageUploadTrait saves relative to 'public/uploads/'
        // Adjust path if your trait saves differently
        return $this->image ? asset($this->image) : null;
        // Or if saved relative to public base path:
        // return $this->image ? asset($this->image) : null;
    }
}

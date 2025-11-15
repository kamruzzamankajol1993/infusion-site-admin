<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_description',
        'type',
        'image_file',
        'youtube_link',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    // UPDATED: Added 'youtube_embed_url'
    protected $appends = ['image_url', 'video_thumbnail_url', 'youtube_embed_url'];

    /**
     * Get the URL for the gallery image.
     */
    public function getImageUrlAttribute(): ?string
    {
        // UPDATED: Added 'uploads/' to match your controller's logic
        // This assumes your trait saves the path relative to 'uploads/'
        // e.g., 'gallery/my-image.jpg'
        return $this->image_file ? asset($this->image_file) : null;
    }

     /**
     * Get the embed URL for the YouTube video.
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if ($this->type !== 'video' || !$this->youtube_link) {
            return null;
        }

        $videoId = null;
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->youtube_link, $match)) {
            $videoId = $match[1];
        }

        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
    }

     /**
     * Get a thumbnail URL for the YouTube video.
     */
      public function getVideoThumbnailUrlAttribute(): ?string
      {
        if ($this->type !== 'video' || !$this->youtube_link) {
            return null;
        }
        $videoId = null;
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->youtube_link, $match)) {
            $videoId = $match[1];
        }
        return $videoId ? "https://img.youtube.com/vi/{$videoId}/sddefault.jpg" : null;
    }
}
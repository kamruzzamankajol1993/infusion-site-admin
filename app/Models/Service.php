<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'display_order', // <-- ADD THIS
        'homepage_display_order',
    ];

    /**
     * Get the keypoints associated with the service.
     */
    public function keypoints(): HasMany
    {
        return $this->hasMany(ServiceKeypoint::class);
    }
}
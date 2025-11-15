<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'service',
        'slug',
        'description',
        'client_id',
        'country_id',
        'category_id',
        'status',
        'agreement_signing_date',
    'is_flagship', // <-- ADDED
    ];

    // Cast status enum if needed in newer Laravel versions (optional but good practice)
    protected $casts = [
        'is_flagship' => 'boolean', // <-- ADDED
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id'); // Explicit foreign key
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(ProjectGallery::class);
    }
}
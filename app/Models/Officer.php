<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str; // Import Str facade
class Officer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email', // Added
        'phone', // Added
        'mobile_number',
        'image',
        'start_date',
        'end_date',
        'description',
        'status',
        'slug',                          // <-- ADDED
        'show_profile_details_button', // <-- ADDED
    ];

    protected $casts = [                    // <-- ADDED Casts
        'show_profile_details_button' => 'boolean',
        'status' => 'boolean', // Also good to cast status explicitly
    ];

    /**
     * The categories that belong to the officer (Many-to-Many).
     */
    public function categories() {
        return $this->belongsToMany(OfficerCategory::class, 'officer_officer_category')
                    ->withPivot('order_column') // Make pivot column accessible
                    ->orderBy('pivot_order_column', 'asc'); // Order by pivot when accessed
    }

    /**
     * Get the multiple department info entries for the officer (One-to-Many).
     */
    public function departmentInfos()
    {
        return $this->hasMany(DepartmentInfo::class);
    }

    /**
     * Get the social links for the officer (One-to-Many).
     */
    public function socialLinks()
    {
        return $this->hasMany(OfficerSocialLink::class);
    }

    public function expertAreas(): HasMany // Added this relationship
    {
        return $this->hasMany(OfficerExpertArea::class);
    }

    // --- Optional: Add a method to generate slug base ---
    // This isn't strictly necessary if using an Observer but can be helpful
    public function generateSlugFromName(): string
    {
        return Str::slug($this->name);
    }
    // --- End Optional ---
}
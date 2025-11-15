<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplicant extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_applicants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_id',
        'full_name',
        'email',
        'phone_number',
        'qualification',
        'cv',
        'additional_information',
        // --- NEW FIELDS ---
        'date_of_birth',
        'educational_background',
        'working_experience',
        'address',
        'total_year_of_experience',
        // --- END NEW FIELDS ---
    ];

    /**
     * Get the Career (Job Posting) associated with this application.
     */
    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class, 'job_id');
    }

    /**
     * Accessor for the CV URL.
     * Assumes CVs are stored directly in public/uploads/cvs/
     * Adjust path if stored differently.
     *
     * @return string|null
     */
    public function getCvUrlAttribute(): ?string
    {
        return $this->cv ? asset('public/' . $this->cv) : null;
    }
}
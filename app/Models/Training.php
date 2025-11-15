<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug', // <-- ADDED
        'description',
        'learn_from_training',
        'who_should_attend',
        'methodology',
        'training_time',
        'training_venue',
        'deadline_for_registration',
        'start_date',
        'end_date',
        'training_fee',
        // 'document_one',
        // 'document_two',
        // 'document_three',
        // 'document_four',
        'image',
        'status', // upcoming, running, postponed, complete
    ];

    /**
     * Get the skills associated with the training.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(TrainingSkill::class);
    }

    /**
     * --- ADD THIS RELATIONSHIP ---
     * Get the documents associated with the training.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(TrainingDocument::class);
    }
    // --- END ADD ---

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'deadline_for_registration' => 'date:Y-m-d',
    ];
}
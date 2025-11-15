<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingEnrollment extends Model
{
    use HasFactory;

    protected $table = 'training_enrollments';

    protected $fillable = [
        'training_id',
        'name',
        'designation',
        'organization',
        'experience',
        'highest_degree',
        'address',
        'email',
        'mobile',
        'telephone',
        'fax',
        'payment_method',
        'status',
    ];

    /**
     * Get the training this enrollment is for.
     */
    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }
}
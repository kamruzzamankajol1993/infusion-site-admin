<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingSkill extends Model
{
    use HasFactory;

    protected $table = 'training_skills';

    protected $fillable = [
        'training_id',
        'skill_name',
    ];

    /**
     * Get the training that owns the skill.
     */
    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }
}
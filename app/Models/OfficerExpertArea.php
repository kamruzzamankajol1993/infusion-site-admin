<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo

class OfficerExpertArea extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'expert_areas'; // Specify table name if not plural model name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'officer_id',
        'expert_area',
    ];

    /**
     * Get the officer that owns this expert area.
     */
    public function officer(): BelongsTo
    {
        return $this->belongsTo(Officer::class);
    }
}
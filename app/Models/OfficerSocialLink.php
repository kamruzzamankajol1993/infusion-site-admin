<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficerSocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'officer_id',
        'title',
        'link',
    ];

    /**
     * Get the officer that owns this social link.
     */
    public function officer()
    {
        return $this->belongsTo(Officer::class);
    }
}
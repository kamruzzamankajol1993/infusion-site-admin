<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IifcStrength extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'iifc_strengths'; // Explicitly define table name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ongoing_project',
        'complete_projects',
        'countries', // Renamed from 'countrier'
        'years_in_business',
    ];

    /**
     * The attributes that should be cast.
     * Ensures these are always treated as integers.
     * @var array
     */
     protected $casts = [
        'ongoing_project' => 'integer',
        'complete_projects' => 'integer',
        'countries' => 'integer',
        'years_in_business' => 'integer',
    ];
}
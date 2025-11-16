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
        'projects',
        'products',
        'experts', // Renamed from 'countrier'
        'countries',
        'happy_clients',
        'yrs_experienced',
    ];

    /**
     * The attributes that should be cast.
     * Ensures these are always treated as integers.
     * @var array
     */
     protected $casts = [
        'projects' => 'integer',
        'products' => 'integer',
        'experts' => 'integer',
        'countries' => 'integer',
        'happy_clients' => 'integer',
        'yrs_experienced' => 'integer',
    ];
}
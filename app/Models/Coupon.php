<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'amount',
        'expire_date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => 'boolean',
        'expire_date' => 'date',
    ];
    
    // Helper to check validity
    public function isValid()
    {
        if (!$this->status) return false;
        if ($this->expire_date && $this->expire_date->isPast()) return false;
        return true;
    }
}
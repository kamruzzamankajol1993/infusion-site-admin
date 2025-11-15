<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SystemInformation extends Model
{
    use HasFactory;

    protected $fillable = [
            'ins_name',
            'logo',
            'rectangular_logo',
            'description',
            'develop_by',
            'icon',
            'address',
            'address_two', // ADDED
            'email',
            'email_two', // ADDED
            'phone',
            'phone_two', // ADDED
            'main_url',
            'front_url',
    ];
}
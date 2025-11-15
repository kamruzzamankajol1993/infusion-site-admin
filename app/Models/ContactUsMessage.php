<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUsMessage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Use plural snake_case name by convention.
     * @var string
     */
    protected $table = 'contact_us_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'mobilenumber',
        'message',
    ];
}
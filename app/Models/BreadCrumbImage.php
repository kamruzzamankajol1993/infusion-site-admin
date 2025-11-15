<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class BreadCrumbImage extends Model
{
     use HasFactory;

     protected $fillable = [
        'name',
        'type',
        'logo',
    ];
}

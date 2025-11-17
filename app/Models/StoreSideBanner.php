<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSideBanner extends Model
{
    use HasFactory;

    protected $table = 'store_side_banners';

    protected $fillable = [
        'top_image',
        'top_link',
        'bottom_image',
        'bottom_link',
    ];
}
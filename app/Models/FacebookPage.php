<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookPage extends Model
{
    use HasFactory;
    protected $table = 'facebook_page';
    protected $fillable = [
        'header_title',
        'intro_title', 'intro_description', 'intro_image',
        'pricing_title', 'pricing_description',
        'more_services_title', 'more_services_description',
    ];
}
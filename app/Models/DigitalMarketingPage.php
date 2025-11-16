<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalMarketingPage extends Model
{
    use HasFactory;
    protected $table = 'digital_marketing_page';
    protected $fillable = [
        'hero_title', 'hero_description', 'hero_button_text',
        'intro_image', 'intro_title', 'intro_description', 'intro_button_text',
        'consultant_title', 'consultant_description', 'consultant_button_text',
        'growth_title', 'growth_description',
        'solutions_subtitle', 'solutions_title', 'solutions_description',
    ];
}
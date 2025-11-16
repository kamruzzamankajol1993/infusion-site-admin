<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class UkCompanyPage extends Model {
    use HasFactory;
    protected $table = 'uk_company_page';
    protected $fillable = [
        'hero_subtitle_top', 'hero_title', 'hero_description', 'hero_button_text', 'hero_button_link', 'hero_image',
        'carbon_badge_text',
        'pricing_title', 'pricing_description',
        'testimonial_title',
        'review_title',
    ];
}
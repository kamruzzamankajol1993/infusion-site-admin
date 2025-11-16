<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WebSolutionPage extends Model {
    use HasFactory;
    protected $table = 'web_solution_page';
    protected $fillable = [
        'hero_title', 'hero_description', 'hero_button_text',
        'intro_image', 'intro_title', 'intro_description', 'intro_button_text',
        'pro_title', 'pro_description', 'pro_button_text',
        'checklist_title', 'checklist_description',
        'includes_subtitle', 'includes_title', 'includes_description',
        'providing_subtitle', 'providing_title', 'providing_description',
        'work_subtitle', 'work_title', 'work_description',
        'cta_title', 'cta_description', 'cta_button_text',
        'care_subtitle', 'care_title', 'care_description',
    ];
}
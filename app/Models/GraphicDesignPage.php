<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GraphicDesignPage extends Model
{
    use HasFactory;
    protected $table = 'graphic_design_page';
    protected $fillable = [
        'hero_title', 'hero_description', 'hero_button_text',
        'intro_image', 'intro_title', 'intro_description', 'intro_button_text',
        'consultant_title', 'consultant_description', 'consultant_button_text',
        'checklist_title', 'checklist_description', // Renamed
        'solutions_subtitle', 'solutions_title', 'solutions_description',
    ];
}
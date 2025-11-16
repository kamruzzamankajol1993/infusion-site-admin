<?php
namespace App;
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class VpsPage extends Model {
    use HasFactory;
    protected $table = 'vps_page';
    protected $fillable = [
        'hero_title', 'hero_features', 'hero_button_text', 'hero_button_link', 'hero_image',
        'category_1_title', 'category_2_title', 'category_3_title',
    ];
    protected $casts = [
        'hero_features' => 'array',
    ];
}
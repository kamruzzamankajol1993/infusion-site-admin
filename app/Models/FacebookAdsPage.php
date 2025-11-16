<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FacebookAdsPage extends Model {
    use HasFactory;
    protected $table = 'facebook_ads_page';
    protected $fillable = [
        'hero_title', 'hero_description', 'hero_button_text', 'hero_image',
        'stats_partner_logo', 'stats_partner_title',
        'stats_exp_number', 'stats_exp_title',
        'stats_client_number', 'stats_client_title',
        'stats_revenue_number', 'stats_revenue_title',
        'campaign_section_title', 'campaign_image',
        'pricing_section_title', 'faq_section_title',
        'cta_title', 'cta_button_text', 'cta_button_link',
    ];
}
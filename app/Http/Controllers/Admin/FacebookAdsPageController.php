<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacebookAdsPage;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FacebookAdsPageController extends Controller
{
    use ImageUploadTrait;
    public function __construct() {
         $this->middleware('permission:facebookAdsPageView', ['only' => ['index', 'storeOrUpdate']]); // Create this permission
    }

    public function index(): View {
        $content = FacebookAdsPage::firstOrNew([]);
        return view('admin.facebook_ads_page.index', compact('content'));
    }

    public function storeOrUpdate(Request $request): RedirectResponse {
        $validatedData = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'required|string',
            'hero_button_text' => 'required|string|max:100',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 500x400

            'stats_partner_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:256', // 150x50
            'stats_partner_title' => 'required|string|max:255',
            'stats_exp_number' => 'required|string|max:100',
            'stats_exp_title' => 'required|string|max:255',
            'stats_client_number' => 'required|string|max:100',
            'stats_client_title' => 'required|string|max:255',
            'stats_revenue_number' => 'required|string|max:100',
            'stats_revenue_title' => 'required|string|max:255',
            
            'campaign_section_title' => 'required|string|max:255',
            'campaign_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 500x500

            'pricing_section_title' => 'required|string|max:255',
            'faq_section_title' => 'required|string|max:255',

            'cta_title' => 'required|string|max:255',
            'cta_button_text' => 'required|string|max:100',
            'cta_button_link' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $content = FacebookAdsPage::firstOrCreate([]);
            $data = $validatedData;

            $data['hero_image'] = $this->handleImageUpdate($request, $content, 'hero_image', 'facebook_ads', 500, 400);
            $data['stats_partner_logo'] = $this->handleImageUpdate($request, $content, 'stats_partner_logo', 'facebook_ads', 150, 50);
            $data['campaign_image'] = $this->handleImageUpdate($request, $content, 'campaign_image', 'facebook_ads', 500, 500);

            $content->update($data);
            DB::commit();
            Log::info('Facebook Ads Page content updated successfully.');
            return redirect()->route('facebookAds.page.index')->with('success', 'Content updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update Facebook Ads Page content: ' . $e->getMessage());
             return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update content. Please check logs.']);
        }
    }
}
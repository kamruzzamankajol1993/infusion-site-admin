<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class NavbarSettingController extends Controller
{
    // --- UPDATED: All 22 keys you provided ---
    private $keys = [
        //main nav item name
        'nav_home',
        'nav_about_iifc',
        'nav_services',
        'nav_projects',
        'nav_training',
        'nav_resources',
        'nav_notice',
        //dropdown of training
        'nav_upcomming_training',
        'nav_all_training',
        //drop down of about iifc
        'nav_about_us',
        'nav_board',
        'nav_subscriber',
        'nav_experts',
        'nav_officers',
        'nav_past-chairmen',
        'nav_past-mds', // I corrected 'past-mds' to 'nav_past-mds'
        'nav_contact_us',
        //drop down of nav_resources
        'nav_career',
        'nav_publication',
        'nav_press-release',
        'nav_events',
        'nav_gallery',
        'nav_download',
    ];

    /**
     * Display the form to manage navbar menu names.
     */
    public function index(): View
    {
        // Fetch all settings in one query
        $settings = SiteSetting::whereIn('key', $this->keys)
                               ->pluck('value', 'key');
        
        // Prepare data for the view
        $data = [];
        foreach ($this->keys as $key) {
            $data[$key] = $settings->get($key); // Will be null if not found
        }
        
        return view('admin.settings.navbar', $data);
    }

    /**
     * Store or update the navbar menu names.
     */
    public function storeOrUpdate(Request $request): RedirectResponse
    {
        // Dynamically create validation rules
        $rules = [];
        foreach ($this->keys as $key) {
            $rules[$key] = 'required|string|max:100';
        }

        $validatedData = $request->validate($rules);

        try {
            foreach ($validatedData as $key => $value) {
                // Update or create each setting
                SiteSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }

            // --- UPDATED: Clear the new API cache key ---
            Cache::forget('api_all_menu_labels');

            Log::info('Navbar settings updated successfully.');
            return redirect()->back()->with('success', 'Navbar menu names updated successfully!');

        } catch (Exception $e) {
            Log::error('Failed to update navbar settings: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update settings.');
        }
    }
}
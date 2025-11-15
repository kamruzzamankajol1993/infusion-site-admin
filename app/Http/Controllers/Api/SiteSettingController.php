<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class SiteSettingController extends Controller
{
    /**
     * Get all navbar settings, structured for the frontend.
     *
     * @return JsonResponse
     */
    public function getAllMenuLabels(): JsonResponse
    {
        try {
            // Cache this response for 1 hour
            $settings = Cache::remember('api_all_menu_labels', 3600, function () {
                // Fetch all settings from the DB
                $settingsFromDB = SiteSetting::pluck('value', 'key');

                // Define all default values
                $defaults = [
                    'nav_home' => 'Home',
                    'nav_about_iifc' => 'About IIFC',
                    'nav_services' => 'Services',
                    'nav_projects' => 'Projects',
                    'nav_training' =>'Training',
                    'nav_resources' =>'Resources',
                    'nav_notice' =>'Notice',
                    'nav_upcomming_training'=>'Upcomming Training',
                    'nav_all_training'=>'All Training',
                    'nav_about_us' => 'About Us',
                    'nav_board'=>'Board Of Directors',
                    'nav_subscriber'=>'Subscriber Members',
                    'nav_experts'=>'Our Experts',
                    'nav_officers'=>'Officers',
                    'nav_past-chairmen'=>'Past Chairmen',
                    'nav_past-mds'=>'Past Mds', // Corrected key
                    'nav_contact_us'=>'Contact Us',
                    'nav_career'=>'Career',
                    'nav_publication'=>'Publication',
                    'nav_press-release'=>'Press Release',
                    'nav_events'=>'Event',
                    'nav_gallery'=>'Gallery',
                    'nav_download'=>'Download',
                ];

                // Merge: Values from the DB will overwrite defaults
                return array_merge($defaults, $settingsFromDB->all());
            });

            // Restructure the flat array into a grouped object
            // This makes it *much* easier for your React app to use
            $structuredSettings = [
                'main_nav' => [
                    'home' => $settings['nav_home'],
                    'about_iifc' => $settings['nav_about_iifc'],
                    'services' => $settings['nav_services'],
                    'projects' => $settings['nav_projects'],
                    'training' => $settings['nav_training'],
                    'resources' => $settings['nav_resources'],
                    'notice' => $settings['nav_notice'],
                ],
                'dropdown_about' => [
                    'about_us' => $settings['nav_about_us'],
                    'board' => $settings['nav_board'],
                    'subscriber' => $settings['nav_subscriber'],
                    'experts' => $settings['nav_experts'],
                    'officers' => $settings['nav_officers'],
                    'past_chairmen' => $settings['nav_past-chairmen'],
                    'past_mds' => $settings['nav_past-mds'],
                    'contact_us' => $settings['nav_contact_us'],
                ],
                'dropdown_training' => [
                    'upcoming' => $settings['nav_upcomming_training'],
                    'all' => $settings['nav_all_training'],
                ],
                'dropdown_resources' => [
                    'career' => $settings['nav_career'],
                    'publication' => $settings['nav_publication'],
                    'press_release' => $settings['nav_press-release'],
                    'events' => $settings['nav_events'],
                    'gallery' => $settings['nav_gallery'],
                    'download' => $settings['nav_download'],
                ],
            ];

            return response()->json([
                'data' => $structuredSettings
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch all menu labels: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve settings.'], 500);
        }
    }
}
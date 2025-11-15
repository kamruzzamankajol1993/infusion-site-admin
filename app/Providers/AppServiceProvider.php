<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache; // Import Cache
use App\Models\SystemInformation; // Import the model
use stdClass; // Used for default object
use Illuminate\Support\Facades\View; // 1. Import View facade
use Illuminate\Support\Facades\Schema; // 2. Import Schema facade
use App\Models\EngageSection;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Relation::morphMap([
            'offer' => 'App\Models\Offer',
            'service' => 'App\Models\Service',
        ]);

        /// --- New Optimized Code ---

        // We cache the settings to avoid hitting the DB on every page load.
        // NOTE: You MUST clear this cache in your SystemInformationController@update method
        // by adding this line: Cache::forget('system_settings');
        $settings = Cache::rememberForever('system_settings', function () {
            return SystemInformation::first();
        });

        // Create an empty object if no settings are found to prevent errors
        if (!$settings) {
            $settings = new stdClass();
        }

        // --- Share variables for all views ---
        // This single block replaces all the old frontend/backend/auth logic.
        // We provide defaults for every field to avoid "undefined property" errors.

        // Main variables (used in backend)
        view()->share('ins_name', $settings->ins_name ?? 'Default Site Name');
        view()->share('logo', $settings->logo ?? '');
        view()->share('icon', $settings->icon ?? '');
        view()->share('rectangular_logo', $settings->rectangular_logo ?? ''); // New
        view()->share('ins_add', $settings->address ?? '');
        view()->share('ins_add_two', $settings->address_two ?? ''); // New
        view()->share('ins_email', $settings->email ?? '');
        view()->share('ins_email_two', $settings->email_two ?? ''); // New
        view()->share('ins_phone', $settings->phone ?? '');
        view()->share('ins_phone_two', $settings->phone_two ?? ''); // New
        view()->share('ins_url', $settings->front_url ?? '');
        view()->share('description', $settings->description ?? '');
        view()->share('develop_by', $settings->develop_by ?? '');

        // Compatibility variables (for "front_")
        // These share the *same data* to ensure your frontend views don't break.
        view()->share('front_icon_name', $settings->icon ?? '');
        view()->share('front_logo_name', $settings->logo ?? '');
        view()->share('front_ins_name', $settings->ins_name ?? 'Default Site Name');
        view()->share('front_ins_add', $settings->address ?? '');
        view()->share('front_ins_email', $settings->email ?? '');
        view()->share('front_ins_phone', $settings->phone ?? '');
        view()->share('front_ins_d', $settings->description ?? '');
        view()->share('front_develop_by', $settings->develop_by ?? '');

        // Share removed variables as empty strings to prevent errors in old views
        view()->share('front_logo_white_name', '');
        view()->share('front_ins_k', '');
        view()->share('tax', '');
        view()->share('charge', '');
        view()->share('keyword', '');

        /// --- End New Code ---


        // 4. Add this View Composer
        // We assume your sidebar view file is at 'resources/views/admin/master/sidebar.blade.php'
        // If it's elsewhere, change 'admin.master.sidebar' to match its path.
        View::composer('admin.include.sidebar', function ($view) {
            
            // Set default fallbacks in case DB is empty
            $engageTitles = [1 => 'Single Source Selection', 2 => 'Tendering']; 

            // Check if the table exists (prevents errors during migrations)
            if (Schema::hasTable('engage_sections')) {
                try {
                    // Fetch titles for ID 1 and 2
                    $sections = EngageSection::whereIn('id', [1, 2])
                                             ->pluck('title', 'id');
                    
                    // Overwrite defaults if titles exist and are not empty
                    if ($sections->has(1) && !empty($sections[1])) {
                        $engageTitles[1] = $sections[1];
                    }
                    if ($sections->has(2) && !empty($sections[2])) {
                        $engageTitles[2] = $sections[2];
                    }

                } catch (\Exception $e) {
                    // Log any error, but the app will continue with default titles
                    \Illuminate\Support\Facades\Log::error('Failed to fetch EngageSection titles for sidebar: ' . $e->getMessage());
                }
            }
            
            // Share the $engageTitles array with the sidebar view
            $view->with('engageTitles', $engageTitles);
        });
    }

}
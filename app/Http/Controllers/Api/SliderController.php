<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\SystemInformation; // <-- 1. Add this import
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; // <-- 2. Add this import
use Exception;

class SliderController extends Controller
{
    /**
     * Display a listing of ALL sliders (no pagination)
     * with full image URLs concatenated in the controller.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // --- 3. Get the base URL from SystemInformation ---
            // We cache this for 1 hour (3600s) to prevent querying the
            // system_information table on every single API request.
            $baseUrl = Cache::remember('system_main_url', 3600, function () {
                return SystemInformation::first()?->value('main_url');
            });

            // Fallback to the .env APP_URL if main_url isn't set in the database
            if (empty($baseUrl)) {
                $baseUrl = config('app.url');
            }
            
            // Ensure the base URL has no trailing slash
            $baseUrl = rtrim($baseUrl, '/');
            // --- End of base URL logic ---


            $query = Slider::query();

            // 1. Search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('subtitle', 'like', '%' . $searchTerm . '%')
                      ->orWhere('short_description', 'like', '%' . $searchTerm . '%');
            }

            // 2. Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            $allowedSorts = ['id', 'title', 'subtitle', 'created_at'];
            
          
                $query->orderBy('display_order', 'asc');
            

            // 3. Get all results (no pagination)
            $sliders = $query->get();

            // --- 4. Manually add the 'image_url' to each slider ---
            // We use 'map' to create a new, modified collection.
            $slidersWithUrls = $sliders->map(function ($slider) use ($baseUrl) {
                
                $imagePath = $slider->image; // Get the relative path
                
                // Handle cases where the image is missing
                if (empty($imagePath)) {
                    $slider->image_url = $baseUrl . '/public/No_Image_Available.jpg';
                } else {
                    // Concatenate the base URL and the path safely
                    // ltrim() prevents 'https://site.com//uploads/image.jpg'
                    $slider->image_url = $baseUrl . '/' . ltrim($imagePath, '/');
                }
                
                return $slider; // Return the modified slider object
            });
            // --- End of URL modification ---
            

            // Return the modified collection
            return response()->json([
                'data' => $slidersWithUrls // <-- 5. Send the modified data
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch sliders: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve sliders.'], 500);
        }
    }
}
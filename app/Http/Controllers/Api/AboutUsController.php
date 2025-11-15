<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\SystemInformation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class AboutUsController extends Controller
{
    /**
     * Get the About Us page content.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $baseUrl = Cache::remember('system_main_url', 3600, function () {
                return SystemInformation::first()?->value('main_url');
            });

            if (empty($baseUrl)) {
                $baseUrl = config('app.url');
            }
            $baseUrl = rtrim($baseUrl, '/');


            $aboutUs = AboutUs::first();

            if (!$aboutUs) {
                return response()->json(['message' => 'About Us content not found.'], 404);
            }

            // Create full image URL
            $imageUrl = '';
            $imagePath = $aboutUs->organogram_image;
            if (empty($imagePath)) {
                $imageUrl = $baseUrl . '/public/No_Image_Available.jpg';
            } else {
                // Assuming image path is like 'public/uploads/...'
                $imageUrl = asset($imagePath); 
            }

            // --- THIS SECTION IS UPDATED ---
            // We NO LONGER use strip_tags() on HTML fields.
            // We only strip tags from plain text fields (vision/mission).
            $formattedData = [
                'mission_title' => $aboutUs->mission_title,
                'mission_description' => $aboutUs->mission_description, // <-- HTML Preserved
                'vision_title' => $aboutUs->vision_title,
                'vision_description' => $aboutUs->vision_description,   // <-- HTML Preserved
                'objectives_title' => $aboutUs->objectives_title,
                'objectives_description' => $aboutUs->objectives_description, // <-- HTML Preserved
                'brief_description' => $aboutUs->brief_description,           // <-- HTML Preserved
                'organogram_image_url' => $imageUrl,
            ];
            
            return response()->json([
                'data' => $formattedData
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch About Us content: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to retrieve About Us content.'
            ], 500);
        }
    }
}
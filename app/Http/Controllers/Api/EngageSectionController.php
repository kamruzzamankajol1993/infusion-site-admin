<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EngageSection; // Your model
use App\Models\SystemInformation; // For base URL
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; // For base URL
use Exception;

class EngageSectionController extends Controller
{
    /**
     * Get the 'How IIFC Can Be Engaged' sections (SSS and Tendering).
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $baseUrl = $this->getBaseUrl(); // Use helper to get base URL

            // Fetch both records (ID 1 and 2)
            // Use keyBy('id') to easily access them later
            $sections = EngageSection::whereIn('id', [1, 2])->get()->keyBy('id');

            // Prepare the data for each section
            $formattedData = [
                'single_source_selection' => [
                    'id' => 1,
                    'title' => $sections->get(1)?->title ?? 'Single Source Selection', // Fallback title
                    'sort_description' => $sections->get(1)?->sort_description ?? null, // <-- ADDED THIS
                    'image_url' => $this->getImageUrl($baseUrl, $sections->get(1)?->image),
                ],
                'tendering' => [
                    'id' => 2,
                    'title' => $sections->get(2)?->title ?? 'Tendering', // Fallback title
                    'sort_description' => $sections->get(2)?->sort_description ?? null, // <-- ADDED THIS
                    'image_url' => $this->getImageUrl($baseUrl, $sections->get(2)?->image),
                ],
            ];

            return response()->json([
                'data' => $formattedData
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch Engage Sections: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve engage sections.'], 500);
        }
    }

    // --- Helper Methods (Copied from other API controllers) ---

    /**
     * Helper to get the cached base URL.
     */
    private function getBaseUrl(): string
    {
        $baseUrl = Cache::remember('system_main_url', 3600, function () {
            // Adjust based on your SystemInformation structure
            return SystemInformation::value('key_value');
            // return SystemInformation::first()?->main_url;
        });

        if (empty($baseUrl)) {
            $baseUrl = config('app.url'); // Fallback to config
        }
        return rtrim($baseUrl, '/');
    }

    /**
     * Helper to build a full image URL.
     */
    private function getImageUrl(string $baseUrl, ?string $imagePath): string
    {
        if (empty($imagePath)) {
            // Ensure placeholder exists
            return $baseUrl . '/public/No_Image_Available.jpg';
        }
        return $baseUrl . '/' . ltrim($imagePath, '/');
    }
}
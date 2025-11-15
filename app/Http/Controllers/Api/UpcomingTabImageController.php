<?php

// --- CORRECTED NAMESPACE ---
namespace App\Http\Controllers\Api;
// --- END CORRECTION ---

use App\Http\Controllers\Controller;
use App\Models\UpcomingTabImage;
use App\Models\SystemInformation; // <-- Needed for base URL
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; // <-- Needed for base URL
use Exception;

class UpcomingTabImageController extends Controller
{
    /**
     * Get the upcoming training tab image URL.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $baseUrl = $this->getBaseUrl(); // Use helper to get base URL
            $imageRecord = UpcomingTabImage::first(); // Get the first (and only) record

            $imageUrl = null;
            if ($imageRecord && $imageRecord->image) {
                $imageUrl = $this->getImageUrl($baseUrl, $imageRecord->image); // Use helper to build URL
            } else {
                // Optionally provide a default placeholder URL if no image is set
                 $imageUrl = $this->getImageUrl($baseUrl, null); // Will use the default placeholder from getImageUrl
            }

            return response()->json([
                'image_url' => $imageUrl
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch Upcoming Tab Image: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve image.'], 500);
        }
    }

    // --- Helper Methods (Copied from Api/TrainingController) ---

    /**
     * Helper to get the cached base URL.
     */
    private function getBaseUrl(): string
    {
        // Adjust this query based on your actual SystemInformation table structure
        $baseUrl = Cache::remember('system_main_url', 3600, function () {
             // Assuming a key-value structure
             return SystemInformation::where('key_name','main_url')->value('key_value');
             // Or if it's just one row with a 'main_url' column:
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
            // Make sure this placeholder exists in your public directory
            return $baseUrl . '/public/No_Image_Available.jpg';
        }
        // Ensure the path starts correctly, handle potential leading slashes
        return $baseUrl . '/' . ltrim($imagePath, '/');
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BreadCrumbImage;
use App\Models\SystemInformation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class BreadCrumbImageController extends Controller
{
    /**
     * Get a single breadcrumb image by its type.
     *
     * @param string $type (e.g., 'about_us', 'all_projects')
     * @return JsonResponse
     */
    public function showByType(string $type): JsonResponse
    {
        try {
            // Get the base URL for constructing image paths
            $baseUrl = $this->getBaseUrl();

            // Find the image data by its 'type'
            $image = BreadCrumbImage::where('type', $type)->first();

            // If not found, return a 404
            if (!$image) {
                return response()->json([
                    'message' => 'Breadcrumb image not found for this type.',
                    // Provide a default fallback image
                    'data' => [
                        'name' => 'Error',
                        'logo_url' => $this->getImageUrl($baseUrl, null) // This returns the 'No_Image_Available.jpg'
                    ]
                ], 404);
            }

            // If found, format the data and return it
            $formattedData = [
                'name' => $image->name,
                'logo_url' => $this->getImageUrl($baseUrl, $image->logo)
            ];
            
            return response()->json([
                'data' => $formattedData
            ]);

        } catch (Exception $e) {
            Log::error("API: Failed to fetch breadcrumb image for type '{$type}': " . $e->getMessage());
            return response()->json([
                'error' => 'Failed to retrieve breadcrumb image.'
            ], 500);
        }
    }


    // --- Helper Methods (Copied from your other API controllers) ---

    /**
     * Helper to get the cached base URL.
     */
    private function getBaseUrl(): string
    {
        $baseUrl = Cache::remember('system_main_url', 3600, function () {
            return SystemInformation::first()?->value('main_url');
        });

        if (empty($baseUrl)) {
            $baseUrl = config('app.url');
        }
        return rtrim($baseUrl, '/');
    }

    /**
     * Helper to build a full image URL.
     */
    private function getImageUrl(string $baseUrl, ?string $imagePath): string
    {
        if (empty($imagePath)) {
            // Make sure your 'public' folder has this fallback image
            return $baseUrl . '/public/No_Image_Available.jpg';
        }
        // Assumes path is like 'public/uploads/breadcrumb_images/...'
        return $baseUrl . '/public/' . ltrim($imagePath, '/');
    }
}
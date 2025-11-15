<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\SystemInformation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\Request;
class ServiceController extends Controller
{
    /**
     * Get the latest 6 services for the home page.
     *
     * @return JsonResponse
     */
    public function homePageServices(): JsonResponse
    {
        try {
            // --- Get Base URL (Cached for Performance) ---
            $baseUrl = Cache::remember('system_main_url', 3600, function () {
                return SystemInformation::first()?->value('main_url');
            });

            if (empty($baseUrl)) {
                $baseUrl = config('app.url');
            }
            $baseUrl = rtrim($baseUrl, '/');
            

            // --- UPDATED QUERY ---
            // Fetch services that have a homepage_display_order,
            // and sort them by that order.
            $services = Service::whereNotNull('homepage_display_order')
                               ->orderBy('homepage_display_order', 'asc')
                               ->get();
            // --- END UPDATED QUERY ---


            // --- Format the output ---
            $formattedServices = $services->map(function ($service) use ($baseUrl) {
                
                // a. Create the full image_url
                $imageUrl = '';
                $imagePath = $service->image;
                if (empty($imagePath)) {
                    $imageUrl = $baseUrl . '/public/No_Image_Available.jpg'; // Default image
                } else {
                    $imageUrl = $baseUrl . '/' . ltrim($imagePath, '/');
                }

                // b. Remove HTML tags and truncate the description
                $plainTextDescription = strip_tags($service->description);
                $shortDescription = Str::limit($plainTextDescription, 200);

                // c. Return only the requested fields
                return [
                    'title' => $service->title,
                    'description' => $shortDescription,
                    'image_url' => $imageUrl,
                ];
            });
            
            // --- Return the formatted data ---
            return response()->json([
                'data' => $formattedServices
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch home page services: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to retrieve services.'
            ], 500);
        }
    }


    public function index(Request $request): JsonResponse
    {
        try {
            // --- 1. Get Base URL (Cached) ---
            $baseUrl = Cache::remember('system_main_url', 3600, function () {
                return SystemInformation::first()?->value('main_url');
            });
            if (empty($baseUrl)) {
                $baseUrl = config('app.url');
            }
            $baseUrl = rtrim($baseUrl, '/');

            // --- 2. Get per_page from request, default to 15 ---
            $perPage = $request->input('per_page', 15);

            // --- 3. Query, Eager-Load, and Paginate ---
            // We eager-load 'keypoints' for high performance
            $services = Service::with('keypoints')
                               ->orderBy('display_order','asc') // Order by newest
                               ->paginate($perPage);

            // --- 4. Transform the paginated data ---
            // Use 'through()' to modify the items *within* the paginator
            // This keeps all pagination meta-data (links, total, etc.)
            $services->through(function ($service) use ($baseUrl) {
                
                // a. Create full image_url
                $imageUrl = '';
                $imagePath = $service->image;
                if (empty($imagePath)) {
                    $imageUrl = $baseUrl . '/public/No_Image_Available.jpg';
                } else {
                    $imageUrl = $baseUrl . '/' . ltrim($imagePath, '/');
                }

                // b. Clean (strip_tags) and truncate description
                $plainText = strip_tags($service->description);
                $shortDescription = Str::limit($plainText, 300);

                // c. Get keypoints as a simple array of strings
                // This is efficient because 'keypoints' was eager-loaded
                $keypointList = $service->keypoints->pluck('keypoint');

                // d. Return the formatted array
                return [
                    'id' => $service->id,
                    'title' => $service->title,
                    'description' => $shortDescription,
                    'image_url' => $imageUrl,
                    'keypoints' => $keypointList,
                ];
            });

            // --- 5. Return the complete paginated JSON response ---
            return response()->json($services);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch paginated services: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve services.'], 500);
        }
    }

    public function show(Service $service): JsonResponse
    {
        try {
            // --- 1. The service is already fetched. Now load its keypoints.
            $service->load('keypoints');

            // --- 2. Get Base URL (Cached) ---
            $baseUrl = Cache::remember('system_main_url', 3600, function () {
                return SystemInformation::first()?->value('main_url');
            });
            if (empty($baseUrl)) {
                $baseUrl = config('app.url');
            }
            $baseUrl = rtrim($baseUrl, '/');

            // --- 3. Create full image_url ---
            $imageUrl = '';
            $imagePath = $service->image;
            if (empty($imagePath)) {
                $imageUrl = $baseUrl . '/public/No_Image_Available.jpg';
            } else {
                $imageUrl = $baseUrl . '/' . ltrim($imagePath, '/');
            }

            // --- 4. Clean the FULL description (no truncation) ---
            $plainTextDescription = $service->description;

            // --- 5. Get keypoints as a simple array ---
            $keypointList = $service->keypoints->pluck('keypoint');

            // --- 6. Format the final output ---
            $formattedData = [
                'id' => $service->id,
                'title' => $service->title,
                'description' => $plainTextDescription, // Full description
                'image_url' => $imageUrl,
                'keypoints' => $keypointList,
            ];

            // --- 7. Return the data ---
            return response()->json(['data' => $formattedData]);

        } catch (Exception $e) {
            // This will catch errors, e.g., if the service isn't found
            Log::error("API: Failed to fetch service detail for ID {$service->id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve service details.'], 500);
        }
    }
}
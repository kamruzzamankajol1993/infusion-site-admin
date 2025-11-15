<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PressRelease;
use App\Models\SystemInformation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class PressReleaseController extends Controller
{
    /**
     * Get a paginated list of all press releases.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $baseUrl = $this->getBaseUrl();
            $perPage = $request->input('per_page', 10);

            // Use the new release_date for ordering, fallback to created_at
            $pressReleases = PressRelease::latest('release_date')->latest('created_at')->paginate($perPage);

            // Format the paginated data
            $pressReleases->through(function ($item) use ($baseUrl) {
                
                $imageUrl = $this->getImageUrl($baseUrl, $item->image);
                $shortDescription = Str::limit(strip_tags($item->description), 150);

                // --- Start: Smart Action Logic ---
                $action_type = 'internal';
                // *** IMPORTANT: Use slug for the internal link ***
                $action_url = '/press_releases/' . $item->slug; 

                if (!empty($item->link) && (str_starts_with($item->link, 'http://') || str_starts_with($item->link, 'https://'))) {
                    $action_type = 'external';
                    $action_url = $item->link;
                }
                // --- End: Smart Action Logic ---

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug, // <-- Added slug
                    'release_date' => $item->release_date, // <-- Added release_date
                    'short_description' => $shortDescription,
                    'image_url' => $imageUrl,
                    'action_type' => $action_type,
                    'action_url' => $action_url,
                ];
            });

            return response()->json($pressReleases);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch paginated press releases: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve press releases.'], 500);
        }
    }

    /**
     * Get the detailed information for a single press release.
     * --- 3. UPDATED ---
     *
     * @param string $slug The press release slug from the URL.
     * @return JsonResponse
     */
    public function show(string $slug): JsonResponse
    {
        try {
            // Find by slug
            $pressRelease = PressRelease::where('slug', $slug)->firstOrFail();

            $baseUrl = $this->getBaseUrl();
            $imageUrl = $this->getImageUrl($baseUrl, $pressRelease->image);

            // Format the final output
            $formattedData = [
                'id' => $pressRelease->id,
                'title' => $pressRelease->title,
                'slug' => $pressRelease->slug, // <-- Added slug
                'release_date' => $pressRelease->release_date, // <-- Added release_date
                'link' => $pressRelease->link,
                'description' => $pressRelease->description,
                'image_url' => $imageUrl,
            ];

            return response()->json(['data' => $formattedData]);

        // Catch if the slug was not found
        } catch (ModelNotFoundException $e) {
            Log::warning("API: Press Release not found for slug '{$slug}'");
            return response()->json(['error' => 'Press release not found.'], 404);
        } catch (Exception $e) {
            Log::error("API: Failed to fetch press release detail for slug '{$slug}': " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve press release details.'], 500);
        }
    }


    // --- Helper Methods ---

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

    private function getImageUrl(string $baseUrl, ?string $imagePath): string
    {
        if (empty($imagePath)) {
            return $baseUrl . '/public/No_Image_Available.jpg';
        }
        return $baseUrl . '/' . ltrim($imagePath, '/');
    }
}
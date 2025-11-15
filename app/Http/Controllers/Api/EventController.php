<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\SystemInformation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class EventController extends Controller
{
    /**
     * Get a paginated list of all published events.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $baseUrl = $this->getBaseUrl();
            $perPage = $request->input('per_page', 9); // Default to 9 (good for a 3-col grid)

            // Query only *published* events (status = 1)
            $events = Event::where('status', 1)
                           ->latest('start_date') // Show upcoming/newest first
                           ->paginate($perPage);

            // Format the paginated data
            $events->through(function ($event) use ($baseUrl) {
                
                $imageUrl = $this->getImageUrl($baseUrl, $event->image);
                $shortDescription = Str::limit(strip_tags($event->description), 150);

                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'slug' => $event->slug,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
                    'time' => $event->time,
                    'short_description' => $shortDescription,
                    'image_url' => $imageUrl,
                ];
            });

            return response()->json($events);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch paginated events: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve events.'], 500);
        }
    }

    /**
     * Get the detailed information for a single published event.
     *
     * @param Event $event (Injected by Route Model Binding)
     * @return JsonResponse
     */
    public function show(string $slug): JsonResponse
    {
        try {
            // Find by slug
            $event = Event::where('slug', $slug)->firstOrFail();

            // CRITICAL: Ensure we don't show draft (status=0) events
            if ($event->status == 0) {
                return response()->json(['error' => 'Event not found.'], 404);
            }

            $baseUrl = $this->getBaseUrl();
            $imageUrl = $this->getImageUrl($baseUrl, $event->image);

            // Format the final output
            $formattedData = [
                'id' => $event->id,
                'title' => $event->title,
                'slug' => $event->slug, // <-- Added slug
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'time' => $event->time,
                'description_full' => $event->description, // Full, clean description
                'image_url' => $imageUrl,
            ];

            return response()->json(['data' => $formattedData]);

        // Catch if the slug was not found
        } catch (ModelNotFoundException $e) {
            Log::warning("API: Event not found for slug '{$slug}'");
            return response()->json(['error' => 'Event not found.'], 404);
        } catch (Exception $e) {
            Log::error("API: Failed to fetch event detail for slug '{$slug}': " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve event details.'], 500);
        }
    }


    // --- Helper Methods ---

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
     * This corrects the path from your model's accessor.
     */
    private function getImageUrl(string $baseUrl, ?string $imagePath): string
    {
        if (empty($imagePath)) {
            return $baseUrl . '/public/No_Image_Available.jpg';
        }
        // Your Admin Controller shows images are in 'uploads/'
        // e.g., 'uploads/' + 'events/my-image.jpg'
        return $baseUrl . '/' . ltrim($imagePath, '/');
    }
} 
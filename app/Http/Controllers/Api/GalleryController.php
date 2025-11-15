<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery; // <-- 1. Import
use Illuminate\Http\Request;      // <-- 2. Import
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class GalleryController extends Controller
{
    /**
     * Get a paginated list of gallery items, filterable by type.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $type = $request->input('type'); // 'image', 'video', or null
            $perPage = $request->input('per_page', 12); // Default 12 items

            $query = Gallery::query();

            // --- 3. Filter based on the 'type' query parameter ---
            if ($type === 'image') {
                $query->where('type', 'image');
            } elseif ($type === 'video') {
                $query->where('type', 'video');
            }
            // If 'type' is missing or "all", we don't add a 'where' clause.

            // --- 4. Select only needed fields and paginate ---
            $galleryItems = $query->latest() // Order by newest first
                                  ->paginate($perPage);

            // The 'image_url', 'video_thumbnail_url', and 'youtube_embed_url'
            // accessors will be automatically added by the model.

            return response()->json($galleryItems);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch gallery items: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve gallery items.'], 500);
        }
    }
}
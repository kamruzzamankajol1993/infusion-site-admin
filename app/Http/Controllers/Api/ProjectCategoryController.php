<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory; // <-- 1. Import
use App\Models\SystemInformation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class ProjectCategoryController extends Controller
{
    /**
     * Get a list of all project categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
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

            // --- 2. Fetch all categories ---
            $categories = ProjectCategory::orderBy('name', 'asc')->get();

            // --- 3. Format the output ---
            $formattedCategories = $categories->map(function ($category) use ($baseUrl) {
                
                $imageUrl = '';
                $imagePath = $category->image; // Assuming 'image' column exists
                if (empty($imagePath)) {
                    $imageUrl = $baseUrl . '/public/No_Image_Available.jpg';
                } else {
                    $imageUrl = $baseUrl . '/' . ltrim($imagePath, '/');
                }

                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug, // <-- ADDED THIS LINE
                    'image_url' => $imageUrl,
                ];
            });
            
            // --- 4. Return the non-paginated data ---
            return response()->json([
                'data' => $formattedCategories
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch project categories: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to retrieve project categories.'
            ], 500);
        }
    }
}
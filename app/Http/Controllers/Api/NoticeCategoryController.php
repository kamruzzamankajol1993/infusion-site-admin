<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NoticeCategory; // <-- 1. Import
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\RedirectResponse;
class NoticeCategoryController extends Controller
{
    /**
     * Get a list of all active notice categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Get all active (status = 1) categories, ordered by name
            $categories = NoticeCategory::where('status', 1)
                                         ->orderBy('id', 'asc')
                                         ->get();

            // Format for the frontend
            $formattedCategories = $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                ];
            });
            
            return response()->json([
                'data' => $formattedCategories
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch notice categories: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to retrieve notice categories.'
            ], 500);
        }
    }
}
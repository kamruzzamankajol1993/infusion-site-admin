<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OfficerCategory; // Import the model
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class OfficerCategoryController extends Controller
{
    /**
     * Get a list of top-level officer categories,
     * including their active children for nested menus.
     *
     * @return JsonResponse
     */
    public function headerCategoryList(): JsonResponse
    {
        try {
            // 1. Eager-load the 'children' relationship
            $categories = OfficerCategory::whereNull('parent_id')
                ->where('status', 1) // Only get active parent categories
                ->with(['children' => function ($query) {
                    // --- THIS IS THE CHANGE ---
                    // Inside the 'with', we only load *active* children
                    // and order them by 'id' in ascending order.
                    $query->where('status', 1)->orderBy('order_column', 'asc');
                }])
                ->orderBy('name', 'asc') // Parent categories are still sorted by name
                ->get();

            // 2. Format the data for the frontend
            $formattedCategories = $categories->map(function ($category) {
                
                $hasChildren = $category->children->isNotEmpty();

                $childrenList = $category->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                    ];
                });

                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'has_children' => $hasChildren, 
                    'children' => $childrenList,    
                ];
            });

            // Return the non-paginated list
            return response()->json([
                'data' => $formattedCategories
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch header categories: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to retrieve categories.'
            ], 500);
        }
    }
}
<?php

namespace App\Http\Controllers\Api; // Ensure correct namespace

use App\Http\Controllers\Controller;
use App\Models\OfficerCategory;
use App\Models\Officer;
use App\Models\SystemInformation; // Import SystemInformation
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;
// Removed Request import as it's not directly used in these methods

class OfficerController extends Controller
{

    /**
     * Get all officers belonging to a parent category OR its children.
     * Sorted by the pivot 'order_column' relative to the PARENT category.
     *
     * @param OfficerCategory $category (This is the PARENT category)
     * @return JsonResponse
     */
    public function showByParentCategory(OfficerCategory $category): JsonResponse
    {
        try {
            $baseUrl = $this->getBaseUrl(); // Use helper

            // Get Parent and Child Category IDs
            $childCategoryIds = $category->children()->pluck('id');
            $parentCategoryId = $category->id;
            $allRelevantCategoryIds = $childCategoryIds->push($parentCategoryId);

            // Fetch all unique, active officers linked to the parent OR any child
            $officers = Officer::where('status', 1)
                ->whereHas('categories', function ($query) use ($allRelevantCategoryIds) {
                    $query->whereIn('officer_category_id', $allRelevantCategoryIds);
                })
                ->with([ // Eager-load relationships needed for sorting and formatting
                    'departmentInfos.designation',
                    'departmentInfos.department',
                    'socialLinks',
                    'expertAreas', // <-- Eager load expert areas
                    // Load the parent category pivot specifically for sorting
                    'categories' => function ($query) use ($parentCategoryId) {
                        $query->where('officer_category_id', $parentCategoryId);
                    }
                ])
                ->get(); // Get the collection

            // Sort the collection in PHP based on the parent's pivot order_column
            $sortedOfficers = $officers->sortBy(function ($officer) {
                $parentCategoryPivot = $officer->categories->first();
                return $parentCategoryPivot ? $parentCategoryPivot->pivot->order_column : PHP_INT_MAX;
            });

            // Format the SORTED data
            $formattedOfficers = $sortedOfficers->values()->map(function ($officer) use ($baseUrl) {
                 // Call helper, relationships are already loaded
                return $this->formatOfficerDetails($officer, $baseUrl);
            });

            return response()->json(['data' => $formattedOfficers]);

        } catch (Exception $e) {
            Log::error("API: Failed to fetch officers for parent category {$category->id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve officers.'], 500);
        }
    }


    /**
     * Get a list of officers for a specific category, sorted by pivot order.
     *
     * @param OfficerCategory $category (Injected by Route Model Binding)
     * @return JsonResponse
     */
    public function showByCategory(OfficerCategory $category): JsonResponse
    {
        try {
            $baseUrl = $this->getBaseUrl();

            // Fetch Officers using the relationship (already ordered by pivot)
            $officers = $category->officers()
                ->where('status', 1) // Only get active officers
                ->with([ // Eager-load all needed relationships
                    'departmentInfos.designation',
                    'departmentInfos.department',
                    'socialLinks',
                    'expertAreas' // <-- Eager load expert areas
                ])
                ->get();

            // Format the output
            $formattedOfficers = $officers->map(function ($officer) use ($baseUrl) {
                // Call helper, relationships are loaded
                return $this->formatOfficerDetails($officer, $baseUrl);
            });

            return response()->json(['data' => $formattedOfficers]);

        } catch (Exception $e) {
            Log::error("API: Failed to fetch officers for category {$category->id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve officers.'], 500);
        }
    }

    /**
     * Get the detailed information for a single officer using their slug.
     *
     * @param string $slug The officer's unique slug from the URL.
     * @return JsonResponse
     */
    public function show(string $slug): JsonResponse // <-- Changed parameter type and name
    {
        try {
            // --- Find officer by slug ---
            $officer = Officer::where('slug', $slug)
                              ->where('status', 1) // Optionally ensure officer is active for public API
                              ->firstOrFail(); // Throws ModelNotFoundException if not found or inactive
            // --- End find ---

            $baseUrl = $this->getBaseUrl();

            // Call the helper, passing true to load relationships
            // The helper already includes slug and show_profile_details_button
            $formattedData = $this->formatOfficerDetails($officer, $baseUrl, true);

            return response()->json(['data' => $formattedData]);

        // Catch ModelNotFoundException specifically for a nicer 404 message
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("API: Officer not found or inactive for slug '{$slug}'");
            return response()->json(['error' => 'Officer not found or is inactive.'], 404);
        } catch (Exception $e) {
            Log::error("API: Failed to fetch officer detail for slug '{$slug}': " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve officer details.'], 500); // Use 500 for general errors
        }
    }


    /**
     * Reusable helper to format a single officer's details for API response.
     *
     * @param Officer $officer
     * @param string $baseUrl
     * @param bool $loadRelationships (Set true for detail view, false if already loaded)
     * @return array
     */
    private function formatOfficerDetails(Officer $officer, string $baseUrl, bool $loadRelationships = false): array
    {
        // Load relationships ONLY if requested (for the 'show' method)
        if ($loadRelationships) {
            $officer->load([
                'categories', // Load all categories for detail view
                'departmentInfos.designation',
                'departmentInfos.department',
                'socialLinks',
                'expertAreas' // <-- Load expert areas
                ]);
        }

        // --- Format Image URL ---
        $imageUrl = '';
        $imagePath = $officer->image; // Path like 'public/uploads/officers/...'
        if (!empty($imagePath)) {
            $imageUrl = asset($imagePath); // Use asset() helper
        } else {
            // Provide a default image URL if needed
            $imageUrl = asset('public/No_Image_Available.jpg'); // Example default
        }

        // --- Format Departments ---
        $departments = $officer->departmentInfos->map(function ($info) {
            
            // --- UPDATED LOGIC ---
            $designationName = $info->designation?->name ?? null;
            $departmentName = $info->department?->name ?? null;
            $additionalText = $info->additional_text ?? null;

            // If designation and department are both null, but additional text exists,
            // use the additional text as the 'designation' and clear the other two.
            if (is_null($designationName) && is_null($departmentName) && !is_null($additionalText)) {
                $designationName = $additionalText; // e.g., "Consultant"
                $departmentName = null;            // Clear department
                $additionalText = null;            // Clear additional text to avoid duplication
            }
            // --- END UPDATED LOGIC ---

            return [
                'designation' => $designationName ?? 'N/A', // Show 'Consultant' or 'Director' or 'N/A'
                'department' => $departmentName ?? 'N/A',  // Show 'Finance' or 'N/A'
                'additional_text' => $additionalText,      // Show '(On Leave)' or null
            ];
        });

        // --- Format Social Links ---
        $socials = $officer->socialLinks->map(function ($link) {
            return [
                'title' => $link->title,
                'link' => $link->link,
            ];
        });

        // --- Format Expert Areas ---
        // Pluck the 'expert_area' string from each related model
        $expertAreasList = $officer->expertAreas->pluck('expert_area')->toArray();
        // -------------------------

        // Format Categories (only needed for detail page where $loadRelationships is true)
        $categoriesList = $loadRelationships ? $officer->categories->pluck('name')->toArray() : null;

        // --- Assemble Final Data ---
        $formattedData = [
            'id' => $officer->id,
            'name' => $officer->name,
            'email' => $officer->email, // <-- Added Email
            'phone' => $officer->phone, // <-- Added Phone
            'mobile_number' => $officer->mobile_number, 
            'image_url' => $imageUrl,
            // --- Return raw description (assuming it contains HTML) ---
            'description' => $officer->description,
            // --------------------------------------------------------
            'departments' => $departments,
            'social_links' => $socials,
            'expert_areas' => $expertAreasList, // <-- Added Expert Areas
            // --- ADDED NEW FIELDS ---
            'slug' => $officer->slug,
            'show_profile_details_button' => (bool) $officer->show_profile_details_button, // Cast to boolean
            // ------------------------

            // --- MOVED DATES OUTSIDE OF 'IF' BLOCK ---
            'start_date' => $officer->start_date,
            'end_date' => $officer->end_date,
            // -----------------------------------------
        ];

        // Add fields specific to the detail view ('show' method)
        if ($loadRelationships) {
            // 'start_date' and 'end_date' were removed from here
            $formattedData['categories'] = $categoriesList; // Include category names in detail view
        }

        return $formattedData;
    }


     /**
     * Helper to get the cached base URL.
     */
    private function getBaseUrl(): string
    {
        // Use Cache facade correctly
        $baseUrl = Cache::remember('system_main_url', 3600, function () {
            // Use full namespace or import at top
            return \App\Models\SystemInformation::first()?->value('main_url');
        });

        if (empty($baseUrl)) {
            $baseUrl = config('app.url'); // Fallback to config
        }
        return rtrim($baseUrl, '/'); // Remove trailing slash
    }

} // End of Api/OfficerController class
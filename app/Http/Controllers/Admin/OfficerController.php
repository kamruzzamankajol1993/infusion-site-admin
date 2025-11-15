<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\OfficerCategory;
use App\Models\Department;
use App\Models\Designation;
use App\Traits\ImageUploadTrait; // Import the trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // Import the File facade
use Exception;
use Illuminate\Http\JsonResponse; // Import JsonResponse
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
class OfficerController extends Controller
{
    use ImageUploadTrait; // Use the trait

    /**
     * Set permissions for the controller methods.
     */
    function __construct()
    {
         // Ensure these permissions exist and are assigned correctly
         $this->middleware('permission:officerAdd|officerUpdate|officerView|officerDelete', ['only' => ['index','store']]);
         $this->middleware('permission:officerAdd', ['only' => ['create','store']]);
         $this->middleware('permission:officerUpdate', ['only' => ['edit','update','updateOrder']]); // Allow updateOrder
         $this->middleware('permission:officerDelete', ['only' => ['destroy']]);
         $this->middleware('permission:officerView', ['only' => ['index','show','data','getOfficersByCategory']]); // Allow getOfficersByCategory
    }


    protected function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        $query = Officer::where('slug', $slug);
        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            // Reset query for the next loop iteration
            $query = Officer::where('slug', $slug);
             if ($ignoreId !== null) {
                 $query->where('id', '!=', $ignoreId);
             }
        }
        return $slug;
    }
    // --- End Helper ---

    /**
     * Display a listing of the resource.
     * Fetches top-level categories for the filter buttons.
     */
    public function index(): View
    {
        // Fetch categories without a parent for the filter buttons
        $topLevelCategories = OfficerCategory::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.officer.index', compact('topLevelCategories'));
    }

    /**
     * Fetch data for the main AJAX table (used when 'All Officers' is selected).
     * Does NOT sort by a specific order column by default anymore.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = Officer::with('categories'); // Eager load categories

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('categories', function($catQuery) use ($searchTerm) {
                          $catQuery->where('name', 'like', '%' . $searchTerm . '%');
                      });
                });
            }

            // Sorting functionality (Defaulting to 'name' or 'id' now)
            $sortColumn = $request->input('sort', 'name'); // Default sort to name
            $sortDirection = $request->input('direction', 'asc');

            $allowedSortColumns = ['id', 'name', 'status', 'start_date', 'end_date']; // Valid columns for sorting the main table
            if (in_array($sortColumn, $allowedSortColumns)) {
                 $query->orderBy($sortColumn, $sortDirection);
            } else {
                 $query->orderBy('name', 'asc'); // Fallback sort
            }

            $paginated = $query->paginate(10); // Adjust page size if needed

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to fetch officer data for table: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve officer list.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = OfficerCategory::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();
        return view('admin.officer.create', compact('categories', 'departments', 'designations'));
    }

    public function store(Request $request): RedirectResponse // <-- Updated return type
    {
        // --- Updated Validation ---
        $request->validate([
            'name' => 'required|string|max:255', // Required
            // 'email' => 'nullable|email|max:255|unique:officers,email',
            'phone' => 'nullable|string',
            'mobile_number' => 'nullable|string|max:20',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // Required
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'status' => 'nullable|boolean',
            'description' => 'nullable|string',
            // --- Categories ARE required ---
            'categories' => 'required|array|min:1', // Changed back to required
            // -----------------------------
            'categories.*' => 'exists:officer_categories,id',
            'department_info' => 'nullable|array', // Kept nullable as per previous request
         'department_info.*.designation_id' => 'nullable|exists:designations,id',
        'department_info.*.department_id' => 'nullable|exists:departments,id',
            'department_info.*.additional_text' => 'nullable|string|max:255',
            'social_links' => 'nullable|array',
            'social_links.*.title' => 'required_with:social_links|string|max:255',
            'social_links.*.link' => 'required_with:social_links|url|max:255',
            'expert_areas' => 'nullable|array',
            'expert_areas.*' => 'nullable|string|max:255',
        ],[
            'phone.digits' => 'The phone number must be exactly 15 digits.',
            'categories.required' => 'Please assign at least one category.', // Added custom message
            'categories.min' => 'Please assign at least one category.'      // Added custom message
        ]);
        // -------------------------

        DB::beginTransaction();
        try {
            $officerData = $request->except([
                'image', 'categories', 'department_info', 'social_links', 'expert_areas', '_token'
            ]);
             $officerData['status'] = $request->input('status', true);
             $officerData['show_profile_details_button'] = $request->input('show_profile_details_button', true);

            // Generate Slug
            $officerData['slug'] = $this->generateUniqueSlug($request->name);

            $tempOfficer = new Officer();
            $imagePath = $this->handleImageUpload($request, $tempOfficer, 'image', 'officers', 313, 374);
            if ($imagePath) {
                $officerData['image'] = $imagePath;
            } else {
                 throw new Exception("Image upload failed or missing.");
            }

            $officer = Officer::create($officerData);

            // Sync Categories (Now guaranteed to have data due to validation)
            $officer->categories()->sync($request->input('categories', []));


            // Create Department Info (if provided)
            if ($request->has('department_info')) {
    foreach ($request->department_info as $info) {
        // --- UPDATED LOGIC ---
        // Save if at least one field is filled
        if (!empty($info['designation_id']) || !empty($info['department_id']) || !empty(trim($info['additional_text'] ?? ''))) {
             $officer->departmentInfos()->create([
                 'designation_id' => $info['designation_id'] ?? null, // Handle null
                 'department_id' => $info['department_id'] ?? null, // Handle null
                 'additional_text' => $info['additional_text'] ?? null,
             ]);
        }
    }
}

            // Create Social Links (if provided)
            if ($request->has('social_links')) {
                foreach ($request->social_links as $link) {
                     if (!empty($link['title']) && !empty($link['link'])) {
                        $officer->socialLinks()->create([
                            'title' => $link['title'],
                            'link' => $link['link'],
                        ]);
                     }
                }
            }

            // Create Expert Areas (if provided)
            if ($request->has('expert_areas')) {
                foreach ($request->expert_areas as $area) {
                    if (!empty(trim($area))) {
                        $officer->expertAreas()->create(['expert_area' => trim($area)]);
                    }
                }
            }

            DB::commit();
            // Use admin. prefix if your routes are defined with it
            return redirect()->route('officer.index')->with('success', 'Officer created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create officer: ' . $e->getMessage());
            $errors = ($e instanceof \Illuminate\Validation\ValidationException)
                      ? $e->errors()
                      : ['error' => 'Failed to create officer. Please check logs.'];
            return redirect()->back()->withInput()->withErrors($errors);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Officer $officer): View
    {
        // Eager load relationships for the view
        $officer->load('categories', 'departmentInfos.designation', 'departmentInfos.department', 'socialLinks');
        return view('admin.officer.show', compact('officer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Officer $officer): View
    {
        $officer->load('categories', 'departmentInfos', 'socialLinks'); // Eager load
        $categories = OfficerCategory::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();

        $selectedCategories = $officer->categories->pluck('id')->toArray();

        return view('admin.officer.edit', compact('officer', 'categories', 'departments', 'designations', 'selectedCategories'));
    }

    public function update(Request $request, Officer $officer): RedirectResponse
    {
         // --- Updated Validation ---
         $request->validate([
            'name' => 'required|string|max:255', // Required
            // 'email' => ['nullable', 'email', 'max:255', Rule::unique('officers')->ignore($officer->id)],
            'phone' => 'nullable|string',
            'mobile_number' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // Nullable on update
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'status' => 'nullable|boolean',
            'description' => 'nullable|string',
            // --- Categories ARE required ---
            'categories' => 'required|array|min:1', // Changed back to required
            // -----------------------------
            'categories.*' => 'exists:officer_categories,id',
            'department_info' => 'nullable|array', // Kept nullable
            'department_info.*.designation_id' => 'nullable|exists:designations,id',
        'department_info.*.department_id' => 'nullable|exists:departments,id',
            'department_info.*.additional_text' => 'nullable|string|max:255',
            'social_links' => 'nullable|array',
            'social_links.*.title' => 'required_with:social_links|string|max:255',
            'social_links.*.link' => 'required_with:social_links|url|max:255',
            'expert_areas' => 'nullable|array',
            'expert_areas.*' => 'nullable|string|max:255',
        ],[
            'phone.digits' => 'The phone number must be exactly 15 digits.',
            'categories.required' => 'Please assign at least one category.', // Added custom message
            'categories.min' => 'Please assign at least one category.'      // Added custom message
        ]);
        // -------------------------

        DB::beginTransaction();
        try {
            $officerData = $request->except([
                'image', 'categories', 'department_info', 'social_links', 'expert_areas', '_token', '_method'
            ]);
             $officerData['status'] = $request->input('status', $officer->status);

             $officerData['show_profile_details_button'] = $request->input('show_profile_details_button', $officer->show_profile_details_button);

             // Regenerate Slug only if the Name has changed
             
                 $officerData['slug'] = $this->generateUniqueSlug($request->name, $officer->id); // Pass officer ID to ignore itself
             
             // Otherwise, the existing slug remains unchanged

            $officerData['image'] = $this->handleImageUpdate($request, $officer, 'image', 'officers', 313, 374);

            $officer->update($officerData);

            // Sync Categories (Now guaranteed to have data)
            $officer->categories()->sync($request->input('categories', []));


            // Re-create Department Info
            $officer->departmentInfos()->delete();
            if ($request->has('department_info')) {
    foreach ($request->department_info as $info) {
        // --- UPDATED LOGIC ---
        // Save if at least one field is filled
        if (!empty($info['designation_id']) || !empty($info['department_id']) || !empty(trim($info['additional_text'] ?? ''))) {
             $officer->departmentInfos()->create([
                 'designation_id' => $info['designation_id'] ?? null, // Handle null
                 'department_id' => $info['department_id'] ?? null, // Handle null
                 'additional_text' => $info['additional_text'] ?? null,
             ]);
        }
    }
}

            // Re-create Social Links
            $officer->socialLinks()->delete();
            if ($request->has('social_links')) {
                 foreach ($request->social_links as $link) {
                     if (!empty($link['title']) && !empty($link['link'])) {
                        $officer->socialLinks()->create([
                            'title' => $link['title'],
                            'link' => $link['link'],
                        ]);
                     }
                }
            }

            // Re-create Expert Areas
            $officer->expertAreas()->delete();
            if ($request->has('expert_areas')) {
                foreach ($request->expert_areas as $area) {
                    if (!empty(trim($area))) {
                        $officer->expertAreas()->create(['expert_area' => trim($area)]);
                    }
                }
            }

            DB::commit();
            // Use admin. prefix if needed
            return redirect()->route('officer.index')->with('success', 'Officer updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update officer ID ' . $officer->id . ': ' . $e->getMessage());
             $errors = ($e instanceof \Illuminate\Validation\ValidationException)
                      ? $e->errors()
                      : ['error' => 'Failed to update officer. Please check logs.'];
            return redirect()->back()->withInput()->withErrors($errors);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    // --- MODIFIED RETURN TYPE ---
    public function destroy(Officer $officer): RedirectResponse
    {
        try {
            DB::beginTransaction();
            // Delete image file using File facade and public_path
            // (Assuming handleImageUpload stores relative to public)
            if ($officer->image && File::exists(public_path($officer->image))) {
                File::delete(public_path($officer->image));
            }
            // If using Storage::disk('public'), use:
            // if ($officer->image && Storage::disk('public')->exists($officer->image)) {
            //     Storage::disk('public')->delete($officer->image);
            // }

            // Manually detach from categories before deleting officer
            $officer->categories()->detach();

            // Delete officer (related departmentInfos, socialLinks, expertAreas should cascade if migrations are set up)
            $officer->delete();

            DB::commit();
            // --- MODIFIED RESPONSE ---
            return redirect()->route('officer.index')->with('success', 'Officer deleted successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete officer ID {$officer->id}: " . $e->getMessage());
            // --- MODIFIED RESPONSE ---
             return redirect()->route('officer.index')->with('error', 'Failed to delete officer.');
        }
    }

    /**
     * Fetch officers for a category card view, ordered by the PIVOT column.
     */
    public function getOfficersByCategory(Request $request, $categoryId): JsonResponse
    {
        try {
            $category = OfficerCategory::findOrFail($categoryId);

            // Use the relationship defined on OfficerCategory which orders by pivot_order_column
            $officers = $category->officers()
                                  ->get(['officers.id', 'officers.name', 'officers.image']); // Select fields for cards

            return response()->json($officers);

        } catch (Exception $e) {
            Log::error("Failed to fetch officers for category ID {$categoryId}: " . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve officers.'], 500);
        }
    }

     /**
     * Update the order_column in the PIVOT table (officer_officer_category)
     * for a specific category based on the drag-and-drop array.
     */
    /**
     * Update the order_column in the PIVOT table (officer_officer_category)
     * for a specific category AND ALL ITS DESCENDANT CATEGORIES.
     */
    public function updateOrder(Request $request, $categoryId): JsonResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:officers,id' // Validate each ID
        ]);

        DB::beginTransaction(); // Use a transaction
        try {
            // Eager load all descendants recursively
            $category = OfficerCategory::with('descendants')->findOrFail($categoryId);
            $orderedOfficerIds = $request->input('order');

            // 1. Get the current category ID + all its descendant IDs
            $categoryIdsToUpdate = $category->getAllDescendantIds();
            $categoryIdsToUpdate[] = $category->id; // Add the parent category itself

            // 2. Loop through the *officers* in their new order
            foreach ($orderedOfficerIds as $index => $officerId) {
                
                // 3. Update the pivot table for this officer
                // This updates the 'order_column' for *all* pivot entries
                // where the officer_id matches AND the officer_category_id
                // is in our list (e.g., Cat 3 or Cat 6).
                DB::table('officer_officer_category')
                    ->where('officer_id', $officerId)
                    ->whereIn('officer_category_id', $categoryIdsToUpdate)
                    ->update(['order_column' => $index]);
            }

            DB::commit(); // Commit the transaction

            return response()->json(['message' => 'Officer order updated successfully for this category and its sub-categories.']);

        } catch (Exception $e) {
            DB::rollBack(); // Rollback on error
            Log::error("Failed to update officer order for category ID {$categoryId}: " . $e->getMessage());
            return response()->json(['error' => 'Could not update order.'], 500);
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory; // Import the model
use App\Traits\ImageUploadTrait; // Import the trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // For deleting file
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class ProjectCategoryController extends Controller
{
    use ImageUploadTrait; // Use the image upload trait

    /**
     * Add permissions middleware.
     */
    public function __construct()
    {
         // Adjust permission names as needed
         $this->middleware('permission:projectCategoryView|projectCategoryAdd|projectCategoryUpdate|projectCategoryDelete', ['only' => ['index','data']]);
         $this->middleware('permission:projectCategoryAdd', ['only' => ['store']]);
         $this->middleware('permission:projectCategoryUpdate', ['only' => ['show', 'update']]); // show fetches data for edit
         $this->middleware('permission:projectCategoryDelete', ['only' => ['destroy']]);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        // Build the query to check for existence
        $query = ProjectCategory::where('slug', $slug);

        // If updating, ignore the current item's ID
        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        // Loop until a unique slug is found
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            // Reset query for the next loop
            $query = ProjectCategory::where('slug', $slug);
            if ($ignoreId !== null) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
    // --- END HELPER FUNCTION ---

    /**
     * Display the listing page.
     */
    public function index(): View
    {


        $projetCat =  ProjectCategory::all();

        // foreach($projetCat as $projetCats){


        //     $updateData =  ProjectCategory::find($projetCats->id);
        //     $updateData->slug = $this->generateUniqueSlug($projetCats->name);
        //     $updateData->save();


        // }



        return view('admin.project_category.index');
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = ProjectCategory::query();

            // Search
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            $allowedSorts = ['id', 'name', 'created_at'];
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('id', 'desc'); // Fallback sort
            }

            $paginated = $query->paginate(10); // Adjust page size

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch project categories: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve categories.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage (from Add modal).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:project_categories,name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // Max 1MB example
        ]);

        DB::beginTransaction();
        try {
            $categoryData = $request->only('name');

            // --- Generate and add slug ---
            $categoryData['slug'] = $this->generateUniqueSlug($request->name);
            // --- End slug generation ---

            // Handle image upload
            $tempModel = new ProjectCategory();
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'project_categories', 750, 422); // Use trait
            if ($imagePath) {
                $categoryData['image'] = $imagePath;
            } else {
                 throw new Exception("Category image upload failed or missing.");
            }

            ProjectCategory::create($categoryData);
            DB::commit();

            Log::info('Project Category created successfully.', ['name' => $request->name]);
            return redirect()->route('projectCategory.index')->with('success','Project Category created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create project category: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to create category. Please check logs.']);
        }
    }

    /**
     * Display the specified resource (used to fetch data for edit modal).
     */
    public function show($id): JsonResponse
    {
        try {
            $category = ProjectCategory::findOrFail($id);
            // Add full image URL for preview in edit modal
            if ($category->image) {
                $category->image_url = asset($category->image);
            } else {
                $category->image_url = null;
            }
            return response()->json($category);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to fetch non-existent project category ID {$id}");
             return response()->json(['error' => 'Category not found.'], 404);
        } catch (Exception $e) {
            Log::error("Failed to fetch project category ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve category data.'], 500);
        }
    }

    /**
     * Update the specified resource in storage (from Edit modal).
     * Using POST with _method=PUT.
     */
    public function update(Request $request, $id): RedirectResponse // <-- MODIFIED RETURN TYPE
    {
        // Use Validator::make to manually handle errors and error bags
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:project_categories,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024',
        ]);

        // If validation fails, redirect back with the 'update' error bag
        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator, 'update') // <-- Use named error bag 'update'
                         ->withInput()
                         ->with('error_modal_id', $id); // <-- Add this to re-open the modal
        }

        DB::beginTransaction();
        try {
            $category = ProjectCategory::findOrFail($id); // Find inside transaction
            $categoryData = $request->only('name');

            // --- Generate and add slug ONLY if name changed ---
          
                $categoryData['slug'] = $this->generateUniqueSlug($request->name, $id);
           
            // --- End slug generation ---
 if ($request->hasFile('image')) {
            // Handle image update
            $imagePath = $this->handleImageUpdate($request, $category, 'image', 'project_categories', 750, 422); // Use trait
            $categoryData['image'] = $imagePath; // Trait returns old path if no new image
}
            $category->update($categoryData);
            DB::commit();

            Log::info('Project Category updated successfully.', ['id' => $id]);
            // --- MODIFIED RESPONSE ---
            return redirect()->route('projectCategory.index')->with('success', 'Category updated successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to update project category ID {$id}: " . $e->getMessage());
             // --- MODIFIED RESPONSE ---
             return redirect()->back()
                        ->withErrors(['error' => 'Failed to update category.'], 'update') // <-- Use named bag
                        ->withInput()
                        ->with('error_modal_id', $id); // <-- Also re-open modal on general failure
        }
    }

    /**
     * Remove the specified resource from storage (single delete).
     */
    // --- MODIFIED RETURN TYPE ---
    public function destroy($id): RedirectResponse
    {
        try {
            $category = ProjectCategory::findOrFail($id);
            DB::beginTransaction();

            // Delete image file first (Using public_path based on handleImageUpload)
            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            // If using Storage::disk('public'), use:
            // if ($category->image && Storage::disk('public')->exists($category->image)) {
            //     Storage::disk('public')->delete($category->image);
            // }

            $category->delete();
            DB::commit();

            // --- MODIFIED RESPONSE ---
            return redirect()->route('projectCategory.index')->with('success', 'Category deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             DB::rollBack();
             Log::warning("Attempted to delete non-existent project category ID {$id}");
             // --- MODIFIED RESPONSE ---
             return redirect()->route('projectCategory.index')->with('error', 'Category not found.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete project category ID {$id}: " . $e->getMessage());
            // --- MODIFIED RESPONSE ---
            return redirect()->route('projectCategory.index')->with('error', 'Failed to delete category.');
        }
    }
}
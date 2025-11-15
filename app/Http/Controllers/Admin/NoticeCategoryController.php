<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NoticeCategory; // Import the model
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class NoticeCategoryController extends Controller
{
    /**
     * Add permissions middleware.
     */
    public function __construct()
    {
         // Adjust permission names as needed
         $this->middleware('permission:noticeCategoryView|noticeCategoryAdd|noticeCategoryUpdate|noticeCategoryDelete', ['only' => ['index','data']]);
         $this->middleware('permission:noticeCategoryAdd', ['only' => ['store']]);
         $this->middleware('permission:noticeCategoryUpdate', ['only' => ['show', 'update']]);
         $this->middleware('permission:noticeCategoryDelete', ['only' => ['destroy']]);
    }

    /**
     * Display the listing page.
     */
    public function index(): View
    {
        return view('admin.notice_category.index');
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = NoticeCategory::query();

            // Search
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Sorting
            $sortColumn = $request->input('sort', 'name'); // Default sort
            $sortDirection = $request->input('direction', 'asc');
            $allowedSorts = ['id', 'name', 'created_at'];
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('name', 'asc'); // Fallback
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
            Log::error('Failed to fetch notice categories: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve categories.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage (from Add modal).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:notice_categories,name',
        ]);

        try {
            NoticeCategory::create($request->only('name'));
            Log::info('Notice Category created successfully.', ['name' => $request->name]);
            return redirect()->route('noticeCategory.index')->with('success','Notice Category created successfully!');
        } catch (Exception $e) {
            Log::error('Failed to create notice category: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create category. Please check logs.');
        }
    }

    /**
     * Display the specified resource (used to fetch data for edit modal).
     */
    public function show($id): JsonResponse
    {
        try {
            $category = NoticeCategory::findOrFail($id);
            return response()->json($category);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to fetch non-existent notice category ID {$id}");
             return response()->json(['error' => 'Category not found.'], 404);
        } catch (Exception $e) {
            Log::error("Failed to fetch notice category ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve category data.'], 500);
        }
    }

   /**
     * Update the specified resource in storage (from Edit modal).
     * --- MODIFIED TO RETURN REDIRECT ---
     */
    public function update(Request $request, $id): RedirectResponse // <-- 2. Change return type
    {
         $request->validate([
            'name' => 'required|string|max:255|unique:notice_categories,name,' . $id,
        ]);

        try {
            $category = NoticeCategory::findOrFail($id);
            $category->update($request->only('name'));
            Log::info('Notice Category updated successfully.', ['id' => $id, 'new_name' => $request->name]);
            
            // --- 3. Change to redirect ---
            return redirect()->route('noticeCategory.index')->with('success', 'Category updated successfully');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to update non-existent notice category ID {$id}");
             
             // --- 4. Change to redirect ---
             return redirect()->route('noticeCategory.index')->with('error', 'Category not found.');

        } catch (Exception $e) {
            Log::error("Failed to update notice category ID {$id}: " . $e->getMessage());
            // Check for unique constraint violation specifically
             if ($e instanceof \Illuminate\Database\QueryException && str_contains($e->getMessage(), 'Duplicate entry')) {
                 
                 // --- 5. Change to redirect back with errors ---
                 return redirect()->back()->withInput()->withErrors(['name' => 'The name has already been taken.']);
             }
             
             // --- 6. Change to redirect ---
            return redirect()->back()->withInput()->with('error', 'Failed to update category.');
        }
    }

    /**
     * Remove the specified resource from storage (single delete).
     * --- MODIFIED TO RETURN REDIRECT ---
     */
    public function destroy($id): RedirectResponse // <-- 7. Change return type
    {
        try {
            $category = NoticeCategory::findOrFail($id);
            // ... (deletion logic) ...
            $category->delete();

            // --- 8. Change to redirect ---
            return redirect()->route('noticeCategory.index')->with('success', 'Category deleted successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to delete non-existent notice category ID {$id}");
             
             // --- 9. Change to redirect ---
             return redirect()->route('noticeCategory.index')->with('error', 'Category not found.');

        } catch (Exception $e) {
             if ($e instanceof \Illuminate\Database\QueryException && str_contains($e->getMessage(), 'foreign key constraint fails')) {
                 Log::error("Failed to delete notice category ID {$id} due to foreign key constraint: " . $e->getMessage());
                 
                 // --- 10. Change to redirect ---
                 return redirect()->route('noticeCategory.index')->with('error', 'Cannot delete this category because it is linked to existing notices.');
             }
            Log::error("Failed to delete notice category ID {$id}: " . $e->getMessage());
            
            // --- 11. Change to redirect ---
            return redirect()->route('noticeCategory.index')->with('error', 'Failed to delete category.');
        }
    }
}

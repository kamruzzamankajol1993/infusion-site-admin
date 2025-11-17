<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Add permissions middleware.
     */
    public function __construct()
    {
         // *** IMPORTANT: Create these permissions in your seeder ***
         $this->middleware('permission:categoryView|categoryAdd|categoryUpdate|categoryDelete', ['only' => ['index','data']]);
         $this->middleware('permission:categoryAdd', ['only' => ['store']]);
         $this->middleware('permission:categoryUpdate', ['only' => ['show', 'update']]);
         $this->middleware('permission:categoryDelete', ['only' => ['destroy']]);
    }

    /**
     * Display the listing page.
     */
    public function index(): View
    {
        // Fetch categories for the 'Parent' dropdown in modals
        $categories = Category::where('status', true)->orderBy('name', 'asc')->get();
        return view('admin.category.index', compact('categories'));
    }

   /**
    * Provide data for the AJAX Data Table.
    */
   public function data(Request $request): JsonResponse
    {
        try {
            $query = Category::with('parent'); // Eager load the parent relationship

            // Search
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhereHas('parent', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
            }

            // Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            
            if ($sortColumn == 'parent') {
                // Sort by parent name
                $query->leftJoin('categories as parents', 'categories.parent_id', '=', 'parents.id')
                      ->orderBy('parents.name', $sortDirection)
                      ->select('categories.*'); // Ensure we only select columns from the main table
            } else {
                $query->orderBy($sortColumn, $sortDirection);
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
            Log::error('Failed to fetch categories: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve categories.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage (from Add modal).
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator)
                         ->withInput()
                         ->with('error_modal', 'addModal'); // Tell script to re-open add modal
        }

        try {
            Category::create($request->all());
            Log::info('Category created successfully.', ['name' => $request->name]);
            return redirect()->route('category.index')->with('success','Category created successfully!');

        } catch (Exception $e) {
            Log::error('Failed to create category: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create category. Please check logs.']);
        }
    }

    /**
     * Display the specified resource (used to fetch data for edit modal).
     */
    public function show($id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json($category); 
        } catch (Exception $e) {
             Log::warning("Attempted to fetch non-existent category ID {$id}");
             return response()->json(['error' => 'Category not found.'], 404);
        }
    }

    /**
     * Update the specified resource in storage (from Edit modal).
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($id)],
            'parent_id' => ['nullable', 'exists:categories,id', Rule::notIn([$id])], // Cannot be its own parent
            'status' => 'required|boolean',
        ],[
            'parent_id.not_in' => 'A category cannot be its own parent.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator, 'update') // Tag errors for 'update'
                         ->withInput()
                         ->with('error_modal_id', $id); // Tell script to re-open this specific modal
        }

        try {
            $category = Category::findOrFail($id); 
            $category->update($request->all());
            Log::info('Category updated successfully.', ['id' => $id]);
            return redirect()->route('category.index')->with('success', 'Category updated successfully');

        } catch (Exception $e) {
            Log::error("Failed to update category ID {$id}: " . $e->getMessage());
             return redirect()->back()
                        ->withErrors(['error' => 'Failed to update category.'], 'update') 
                        ->withInput()
                        ->with('error_modal_id', $id);
        }
    }


    /**
     * Remove the specified resource from storage (single delete).
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete(); // Child categories will have parent_id set to null

            return redirect()->route('category.index')->with('success', 'Category deleted successfully.');

        } catch (Exception $e) {
            Log::error("Failed to delete category ID {$id}: " . $e->getMessage());
            return redirect()->route('category.index')->with('error', 'Failed to delete category.');
        }
    }
}
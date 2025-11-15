<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OfficerCategory;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class OfficerCategoryController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:officerCategoryView|officerCategoryAdd|officerCategoryUpdate|officerCategoryDelete', ['only' => ['index','store','destroy','update']]);
         $this->middleware('permission:officerCategoryAdd', ['only' => ['create','store']]);
         $this->middleware('permission:officerCategoryUpdate', ['only' => ['edit','update']]);
         $this->middleware('permission:officerCategoryDelete', ['only' => ['destroy']]);
    }

    public function index(): View
    {
        // Fetch all categories to pass to the modal dropdowns
        $categories = OfficerCategory::orderBy('name', 'asc')->get();
        return view('admin.officerCategory.index', compact('categories'));
    }

    public function data(Request $request)
    {
        try {
            // Eager-load the parent relationship
            $query = OfficerCategory::with('parent');

            if ($request->filled('search')) {
                $query->where('name', 'like', $request->search . '%')
                      // Also allow searching by parent name
                      ->orWhereHas('parent', function($q) use ($request) {
                          $q->where('name', 'like', $request->search . '%');
                      });
            }

            $query->orderBy('id','asc');
            $paginated = $query->paginate(10);

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to fetch officer category data: ' . $e);
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = OfficerCategory::findOrFail($id);
            return response()->json($user);
        } catch (Exception $e) {
            Log::error("Failed to fetch officer category ID {$id}: " . $e);
            return response()->json(['error' => 'Officer category not found.'], 404);
        }
    }

    public function store(Request $request)
    {
        // Add parent_id to validation
        $request->validate([
            'name' => 'required|string|unique:officer_categories,name',
            'parent_id' => 'nullable|exists:officer_categories,id' // Ensures parent_id is valid
        ]);
        
        try {
            OfficerCategory::create($request->all());
            Log::info('Officer Category created successfully.', ['name' => $request->name]);
            return redirect()->back()->with('success','Created successfully!');
        } catch (Exception $e) {
            Log::error('Failed to create officer category: ' . $e);
            return redirect()->back()->with('error', 'Failed to create officer category. Please check logs.');
        }
    }

    public function update(Request $request, $id): RedirectResponse
    {
        // Add parent_id to validation
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:officer_categories,name,' . $id],
            'parent_id' => 'nullable|exists:officer_categories,id' // Ensures parent_id is valid
        ]);

        try {
            $officerCategory = OfficerCategory::findOrFail($id);
            $officerCategory->update($request->all());
            Log::info('Officer Category updated successfully.', ['id' => $id, 'new_name' => $request->name]);
            
            // --- MODIFICATION ---
            // Return a redirect response instead of JSON
            return redirect()->back()->with('success', 'Officer Category updated successfully');
            
        } catch (Exception $e) {
            Log::error("Failed to update officer category ID {$id}: " . $e);

            // --- MODIFICATION ---
            // Return a redirect response with an error
            return redirect()->back()->with('error', 'Failed to update officer category.');
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            OfficerCategory::where('id', $id)->delete();
            Log::info('Officer Category deleted successfully.', ['id' => $id]);
            return redirect()->back()->with('success', 'Officer Category deleted successfully!');
        } catch (Exception $e) {
            Log::error("Failed to delete officer category ID {$id}: " . $e);
            return redirect()->back()->with('error', 'Failed to delete officer category.');
        }
    }

    /**
     * Get child categories for a given parent ID (for AJAX).
     *
     * @param int $id Parent Category ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChildren($id): \Illuminate\Http\JsonResponse
    {
        try {
            $parent = OfficerCategory::findOrFail($id);
            // Fetch children using the relationship (which now includes ordering)
            $children = $parent->children()->get(['id', 'name']); // Only select needed columns
            return response()->json($children);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Parent category not found.'], 404);
        } catch (Exception $e) {
            Log::error("Failed to fetch children for category ID {$id}: " . $e);
            return response()->json(['error' => 'Could not retrieve child categories.'], 500);
        }
    }

    /**
     * Update the order of child categories (for AJAX).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateChildOrder(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'orderedIds' => 'required|array',
            'orderedIds.*' => 'integer|exists:officer_categories,id' // Ensure IDs are valid
        ]);

        $orderedIds = $request->input('orderedIds');

        DB::beginTransaction();
        try {
            foreach ($orderedIds as $index => $categoryId) {
                // Update the order_column based on the array index
                OfficerCategory::where('id', $categoryId)
                               ->update(['order_column' => $index]);
            }
            DB::commit();
            Log::info('Child officer category order updated successfully.');
            return response()->json(['message' => 'Order updated successfully!']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update child officer category order: ' . $e);
            return response()->json(['error' => 'Failed to update order. Please check logs.'], 500);
        }
    }
}
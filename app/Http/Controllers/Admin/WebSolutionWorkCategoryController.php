<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebSolutionWorkCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WebSolutionWorkCategoryController extends Controller
{
    public function __construct() {
         $this->middleware('permission:webSolutionWorkCategoryView|webSolutionWorkCategoryAdd|webSolutionWorkCategoryUpdate|webSolutionWorkCategoryDelete', ['only' => ['index','data']]); // Create permissions
         $this->middleware('permission:webSolutionWorkCategoryAdd', ['only' => ['store']]);
         $this->middleware('permission:webSolutionWorkCategoryUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:webSolutionWorkCategoryDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? WebSolutionWorkCategory::orderBy('order', 'asc')->get() : [];
        return view('admin.web_solution_work_category.index', compact('activeTab', 'items'));
    }
    public function data(Request $request): JsonResponse {
        try {
            $query = WebSolutionWorkCategory::query();
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
            $paginated = $query->paginate(10);
            return response()->json($paginated);
        } catch (Exception $e) { return response()->json(['error' => 'Failed to retrieve data.'], 500); }
    }
    public function store(Request $request): RedirectResponse 
    {
        $request->validate(['name' => 'required|string|max:255|unique:web_solution_work_categories,name']);
        try {
            WebSolutionWorkCategory::create(['name' => $request->name]);
            return redirect()->route('webSolution.workCategory.index')->with('success','Category created successfully!');
        } catch (Exception $e) { 
            
            return redirect()->back()->withInput()->withErrors(['error' => "Failed to create category."]); 
        
        }
    }
    public function show($id): JsonResponse {
        try {
            return response()->json(WebSolutionWorkCategory::findOrFail($id));
        } catch (Exception $e) { return response()->json(['error' => 'Category not found.'], 404); }
    }
    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), ['name' => 'required|string|max:255|unique:web_solution_work_categories,name,' . $id]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        try {
            WebSolutionWorkCategory::findOrFail($id)->update(['name' => $request->name]);
            return redirect()->route('webSolution.workCategory.index')->with('success', 'Category updated successfully');
        } catch (Exception $e) {
             return redirect()->back()->withErrors(['error' => 'Failed to update category.'], 'update')->withInput()->with('error_modal_id', $id);
        }
    }
    public function updateOrder(Request $request): JsonResponse { /* ... same as previous controller ... */ 
        $request->validate(['itemIds' => 'required|array']);
        try {
            foreach ($request->itemIds as $index => $id) {
                WebSolutionWorkCategory::where('id', $id)->update(['order' => $index + 1]);
            }
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500);
        }
    }
    public function destroy($id): RedirectResponse {
        try {
            // Note: Deleting a category will also delete all items in it (due to onDelete('cascade') in migration)
            WebSolutionWorkCategory::findOrFail($id)->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE web_solution_work_categories SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('webSolution.workCategory.index')->with('success', 'Category deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('webSolution.workCategory.index')->with('error', 'Failed to delete category.');
        }
    }
}
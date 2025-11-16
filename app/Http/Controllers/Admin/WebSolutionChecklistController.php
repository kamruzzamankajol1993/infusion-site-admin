<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebSolutionChecklist;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WebSolutionChecklistController extends Controller
{
    public function __construct() {
         $this->middleware('permission:webSolutionChecklistView|webSolutionChecklistAdd|webSolutionChecklistUpdate|webSolutionChecklistDelete', ['only' => ['index','data']]); // Create permissions
         $this->middleware('permission:webSolutionChecklistAdd', ['only' => ['store']]);
         $this->middleware('permission:webSolutionChecklistUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:webSolutionChecklistDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? WebSolutionChecklist::orderBy('order', 'asc')->get() : [];
        return view('admin.web_solution_checklist.index', compact('activeTab', 'items'));
    }
    public function data(Request $request): JsonResponse {
        try {
            $query = WebSolutionChecklist::query();
            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }
            $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
            $paginated = $query->paginate(10);
            return response()->json($paginated);
        } catch (Exception $e) {
            Log::error('Failed to fetch checklist items: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }
    public function store(Request $request): RedirectResponse {
        $request->validate(['title' => 'required|string|max:255']);
        try {
            WebSolutionChecklist::create($request->only('title'));
            return redirect()->route('webSolution.checklist.index')->with('success','Item created successfully!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => "Failed to create item."]);
        }
    }
    public function show($id): JsonResponse {
        try {
            return response()->json(WebSolutionChecklist::findOrFail($id));
        } catch (Exception $e) {
             return response()->json(['error' => 'Item not found.'], 404);
        }
    }
    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), ['title' => 'required|string|max:255']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        try {
            WebSolutionChecklist::findOrFail($id)->update($request->only('title'));
            return redirect()->route('webSolution.checklist.index')->with('success', 'Item updated successfully');
        } catch (Exception $e) {
             return redirect()->back()->withErrors(['error' => 'Failed to update item.'], 'update')->withInput()->with('error_modal_id', $id);
        }
    }
    public function updateOrder(Request $request): JsonResponse {
        $request->validate(['itemIds' => 'required|array']);
        try {
            foreach ($request->itemIds as $index => $id) {
                WebSolutionChecklist::where('id', $id)->update(['order' => $index + 1]);
            }
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500);
        }
    }
    public function destroy($id): RedirectResponse {
        try {
            WebSolutionChecklist::findOrFail($id)->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE web_solution_checklists SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('webSolution.checklist.index')->with('success', 'Item deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('webSolution.checklist.index')->with('error', 'Failed to delete item.');
        }
    }
}
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GraphicDesignChecklist;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GraphicDesignChecklistController extends Controller
{
    public function __construct()
    {
         $this->middleware('permission:graphicDesignChecklistView|graphicDesignChecklistAdd|graphicDesignChecklistUpdate|graphicDesignChecklistDelete', ['only' => ['index','data']]);
         $this->middleware('permission:graphicDesignChecklistAdd', ['only' => ['store']]);
         $this->middleware('permission:graphicDesignChecklistUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:graphicDesignChecklistDelete', ['only' => ['destroy']]);
    }

    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'table');
        $items = [];
        if ($activeTab === 'reorder') {
            $items = GraphicDesignChecklist::orderBy('order', 'asc')->get();
        }
        return view('admin.graphic_design_checklist.index', compact('activeTab', 'items'));
    }

   public function data(Request $request): JsonResponse
    {
        try {
            $query = GraphicDesignChecklist::query();
            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }
            $sortColumn = $request->input('sort', 'order');
            $sortDirection = $request->input('direction', 'asc');
            $query->orderBy($sortColumn, $sortDirection);
            $paginated = $query->paginate(10);
            return response()->json($paginated);
        } catch (Exception $e) {
            Log::error('Failed to fetch checklist items: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['title' => 'required|string|max:255']);
        try {
            GraphicDesignChecklist::create($request->only('title'));
            return redirect()->route('graphicDesign.checklist.index')->with('success','Item created successfully!');
        } catch (Exception $e) {
            Log::error("Failed to create checklist item: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => "Failed to create item."]);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $item = GraphicDesignChecklist::findOrFail($id);
            return response()->json($item);
        } catch (Exception $e) {
             return response()->json(['error' => 'Item not found.'], 404);
        }
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), ['title' => 'required|string|max:255']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        try {
            $item = GraphicDesignChecklist::findOrFail($id);
            $item->update($request->only('title'));
            return redirect()->route('graphicDesign.checklist.index')->with('success', 'Item updated successfully');
        } catch (Exception $e) {
            Log::error("Failed to update checklist item ID {$id}: " . $e->getMessage());
             return redirect()->back()->withErrors(['error' => 'Failed to update item.'], 'update')->withInput()->with('error_modal_id', $id);
        }
    }

    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate(['itemIds' => 'required|array']);
        try {
            foreach ($request->itemIds as $index => $id) {
                GraphicDesignChecklist::where('id', $id)->update(['order' => $index + 1]);
            }
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
        } catch (Exception $e) {
            Log::error('Failed to update checklist order: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500);
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            GraphicDesignChecklist::findOrFail($id)->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE graphic_design_checklists SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('graphicDesign.checklist.index')->with('success', 'Item deleted successfully.');
        } catch (Exception $e) {
            Log::error("Failed to delete checklist item ID {$id}: " . $e->getMessage());
            return redirect()->route('graphicDesign.checklist.index')->with('error', 'Failed to delete item.');
        }
    }
}
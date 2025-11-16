<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalMarketingGrowthItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DigitalMarketingGrowthItemController extends Controller
{
    public function __construct()
    {
         $this->middleware('permission:digitalMarketingGrowthView|digitalMarketingGrowthAdd|digitalMarketingGrowthUpdate|digitalMarketingGrowthDelete', ['only' => ['index','data']]);
         $this->middleware('permission:digitalMarketingGrowthAdd', ['only' => ['store']]);
         $this->middleware('permission:digitalMarketingGrowthUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:digitalMarketingGrowthDelete', ['only' => ['destroy']]);
    }

    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'table');
        $items = [];
        if ($activeTab === 'reorder') {
            $items = DigitalMarketingGrowthItem::orderBy('order', 'asc')->get();
        }
        return view('admin.digital_marketing_growth.index', compact('activeTab', 'items'));
    }

   public function data(Request $request): JsonResponse
    {
        try {
            $query = DigitalMarketingGrowthItem::query();
            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }
            $sortColumn = $request->input('sort', 'order');
            $sortDirection = $request->input('direction', 'asc');
            $query->orderBy($sortColumn, $sortDirection);
            $paginated = $query->paginate(10);
            return response()->json($paginated);
        } catch (Exception $e) {
            Log::error('Failed to fetch growth items: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['title' => 'required|string|max:255']);
        try {
            DigitalMarketingGrowthItem::create($request->only('title'));
            return redirect()->route('digital-marketing-growth.index')->with('success','Item created successfully!');
        } catch (Exception $e) {
            Log::error("Failed to create growth item: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => "Failed to create item."]);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $item = DigitalMarketingGrowthItem::findOrFail($id);
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
            $item = DigitalMarketingGrowthItem::findOrFail($id);
            $item->update($request->only('title'));
            return redirect()->route('digital-marketing-growth.index')->with('success', 'Item updated successfully');
        } catch (Exception $e) {
            Log::error("Failed to update growth item ID {$id}: " . $e->getMessage());
             return redirect()->back()->withErrors(['error' => 'Failed to update item.'], 'update')->withInput()->with('error_modal_id', $id);
        }
    }

    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate(['itemIds' => 'required|array']);
        try {
            foreach ($request->itemIds as $index => $id) {
                DigitalMarketingGrowthItem::where('id', $id)->update(['order' => $index + 1]);
            }
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
        } catch (Exception $e) {
            Log::error('Failed to update growth order: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500);
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            DigitalMarketingGrowthItem::findOrFail($id)->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE digital_marketing_growth_items SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('digital-marketing-growth.index')->with('success', 'Item deleted successfully.');
        } catch (Exception $e) {
            Log::error("Failed to delete growth item ID {$id}: " . $e->getMessage());
            return redirect()->route('digital-marketing-growth.index')->with('error', 'Failed to delete item.');
        }
    }
}
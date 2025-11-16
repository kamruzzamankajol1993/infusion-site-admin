<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebSolutionCareItem; // Changed Model
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class WebSolutionCareItemController extends Controller
{
    use ImageUploadTrait;
    public function __construct() {
         $this->middleware('permission:webSolutionCareItemView|webSolutionCareItemAdd|webSolutionCareItemUpdate|webSolutionCareItemDelete', ['only' => ['index','data']]); // Create permissions
         $this->middleware('permission:webSolutionCareItemAdd', ['only' => ['store']]);
         $this->middleware('permission:webSolutionCareItemUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:webSolutionCareItemDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? WebSolutionCareItem::orderBy('order', 'asc')->get() : [];
        return view('admin.web_solution_care_item.index', compact('activeTab', 'items'));
    }
    public function data(Request $request): JsonResponse {
        try {
            $query = WebSolutionCareItem::query();
            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }
            $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
            $paginated = $query->paginate(10);
            return response()->json($paginated);
        } catch (Exception $e) { return response()->json(['error' => 'Failed to retrieve data.'], 500); }
    }
    public function store(Request $request): RedirectResponse {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:256', // 100x60
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
        ]);
        try {
            $data = $request->only('title', 'button_text', 'button_link');
            $data['image'] = $this->handleImageUpload($request, new WebSolutionCareItem(), 'image', 'web_solution_care', 100, 60);
            if (!$data['image']) throw new Exception("Image upload failed.");
            WebSolutionCareItem::create($data);
            return redirect()->route('webSolution.care-item.index')->with('success','Item created successfully!');
        } catch (Exception $e) { return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]); }
    }
    public function show($id): JsonResponse {
        try {
            $item = WebSolutionCareItem::findOrFail($id);
            if ($item->image) $item->image_url = asset($item->image);
            return response()->json($item);
        } catch (Exception $e) { return response()->json(['error' => 'Item not found.'], 404); }
    }
    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:256', // 100x60
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        try {
            $item = WebSolutionCareItem::findOrFail($id);
            $data = $request->only('title', 'button_text', 'button_link');
            if ($request->hasFile('image')) {
                $data['image'] = $this->handleImageUpdate($request, $item, 'image', 'web_solution_care', 100, 60);
            }
            $item->update($data);
            return redirect()->route('webSolution.care-item.index')->with('success', 'Item updated successfully');
        } catch (Exception $e) {
             return redirect()->back()->withErrors(['error' => 'Failed to update item.'], 'update')->withInput()->with('error_modal_id', $id);
        }
    }
    public function updateOrder(Request $request): JsonResponse { /* ... same as previous controller ... */ 
        $request->validate(['itemIds' => 'required|array']);
        try {
            foreach ($request->itemIds as $index => $id) {
                WebSolutionCareItem::where('id', $id)->update(['order' => $index + 1]);
            }
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500);
        }
    }
    public function destroy($id): RedirectResponse {
        try {
            $item = WebSolutionCareItem::findOrFail($id);
            if ($item->image && File::exists(public_path($item->image))) File::delete(public_path($item->image));
            $item->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE web_solution_care_items SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('webSolution.care-item.index')->with('success', 'Item deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('webSolution.care-item.index')->with('error', 'Failed to delete item.');
        }
    }
}
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacebookMoreService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FacebookMoreServiceController extends Controller
{
    // Reusing the "Include" controller logic as it's identical
    public function __construct() {
         $this->middleware('permission:facebookMoreServiceView|facebookMoreServiceAdd|facebookMoreServiceUpdate|facebookMoreServiceDelete', ['only' => ['index','data']]); // Create permissions
         $this->middleware('permission:facebookMoreServiceAdd', ['only' => ['store']]);
         $this->middleware('permission:facebookMoreServiceUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:facebookMoreServiceDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? FacebookMoreService::orderBy('order', 'asc')->get() : [];
        return view('admin.facebook_more_service.index', compact('activeTab', 'items'));
    }
    public function data(Request $request): JsonResponse {
        try {
            $query = FacebookMoreService::query();
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
            'description' => 'required|string|max:255',
            'icon_name' => 'required|string|max:100',
            'link_text' => 'required|string|max:100',
            'link_url' => 'required|string|max:255',
        ]);
        try {
            FacebookMoreService::create($request->all());
            return redirect()->route('facebookPage.service.index')->with('success','Service created successfully!');
        } catch (Exception $e) { return redirect()->back()->withInput()->withErrors(['error' => "Failed to create service."]); }
    }
    public function show($id): JsonResponse {
        try {
            return response()->json(FacebookMoreService::findOrFail($id));
        } catch (Exception $e) { return response()->json(['error' => 'Service not found.'], 404); }
    }
    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'icon_name' => 'required|string|max:100',
            'link_text' => 'required|string|max:100',
            'link_url' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        try {
            FacebookMoreService::findOrFail($id)->update($request->all());
            return redirect()->route('facebookPage.service.index')->with('success', 'Service updated successfully');
        } catch (Exception $e) {
             return redirect()->back()->withErrors(['error' => 'Failed to update service.'], 'update')->withInput()->with('error_modal_id', $id);
        }
    }
    public function updateOrder(Request $request): JsonResponse { 
        $request->validate(['itemIds' => 'required|array']);
        try {
            foreach ($request->itemIds as $index => $id) {
                FacebookMoreService::where('id', $id)->update(['order' => $index + 1]);
            }
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
        } catch (Exception $e) { return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500); }
    }
    public function destroy($id): RedirectResponse {
        try {
            FacebookMoreService::findOrFail($id)->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE facebook_more_services SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('facebookPage.service.index')->with('success', 'Service deleted successfully.');
        } catch (Exception $e) { return redirect()->route('facebookPage.service.index')->with('error', 'Failed to delete service.'); }
    }
}
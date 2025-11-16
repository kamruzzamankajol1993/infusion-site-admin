<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacebookPricingPackage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FacebookPricingPackageController extends Controller
{
    public function __construct() {
         $this->middleware('permission:facebookPackageView|facebookPackageAdd|facebookPackageUpdate|facebookPackageDelete', ['only' => ['index','data']]); // Create permissions
         $this->middleware('permission:facebookPackageAdd', ['only' => ['store']]);
         $this->middleware('permission:facebookPackageUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:facebookPackageDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? FacebookPricingPackage::orderBy('order', 'asc')->get() : [];
        return view('admin.facebook_pricing_package.index', compact('activeTab', 'items'));
    }
    public function data(Request $request): JsonResponse {
        try {
            $query = FacebookPricingPackage::query();
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
            'price' => 'required|string|max:100',
            'features' => 'required|array',
            'features.*' => 'required|string|max:255',
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
        ]);
        try {
            FacebookPricingPackage::create($request->all());
            return redirect()->route('facebookPage.package.index')->with('success','Package created successfully!');
        } catch (Exception $e) { return redirect()->back()->withInput()->withErrors(['error' => "Failed to create package."]); }
    }
    public function show($id): JsonResponse {
        try {
            return response()->json(FacebookPricingPackage::findOrFail($id));
        } catch (Exception $e) { return response()->json(['error' => 'Package not found.'], 404); }
    }
    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'price' => 'required|string|max:100',
            'features' => 'required|array',
            'features.*' => 'required|string|max:255',
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        try {
            FacebookPricingPackage::findOrFail($id)->update($request->all());
            return redirect()->route('facebookPage.package.index')->with('success', 'Package updated successfully');
        } catch (Exception $e) {
             return redirect()->back()->withErrors(['error' => 'Failed to update package.'], 'update')->withInput()->with('error_modal_id', $id);
        }
    }
    public function updateOrder(Request $request): JsonResponse { 
        $request->validate(['itemIds' => 'required|array']);
        try {
            foreach ($request->itemIds as $index => $id) {
                FacebookPricingPackage::where('id', $id)->update(['order' => $index + 1]);
            }
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
        } catch (Exception $e) { return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500); }
    }
    public function destroy($id): RedirectResponse {
        try {
            FacebookPricingPackage::findOrFail($id)->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE facebook_pricing_packages SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('facebookPage.package.index')->with('success', 'Package deleted successfully.');
        } catch (Exception $e) { return redirect()->route('facebookPage.package.index')->with('error', 'Failed to delete package.'); }
    }
}
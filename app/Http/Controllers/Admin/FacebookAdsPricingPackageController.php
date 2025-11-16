<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacebookAdsPricingPackage;
use App\Models\FacebookAdsPricingCategory; // Needed for dropdown
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FacebookAdsPricingPackageController extends Controller
{
    public function __construct() {
         $this->middleware('permission:facebookAdsPricingPackageView|facebookAdsPricingPackageAdd|facebookAdsPricingPackageUpdate|facebookAdsPricingPackageDelete', ['only' => ['index','data']]);
         $this->middleware('permission:facebookAdsPricingPackageAdd', ['only' => ['store']]);
         $this->middleware('permission:facebookAdsPricingPackageUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:facebookAdsPricingPackageDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? FacebookAdsPricingPackage::with('category')->orderBy('order', 'asc')->get() : [];
        $categories = FacebookAdsPricingCategory::orderBy('name', 'asc')->pluck('name', 'id');
        return view('admin.facebook_ads_pricing_package.index', compact('activeTab', 'items', 'categories'));
    }
    public function data(Request $request): JsonResponse {
        $query = FacebookAdsPricingPackage::with('category');
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('category', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }
        $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
        return response()->json($query->paginate(10));
    }
    public function store(Request $request): RedirectResponse {
        $request->validate([
            'category_id' => 'required|exists:facebook_ads_pricing_categories,id',
            'title' => 'required|string|max:255',
            'price' => 'required|string|max:100',
            'price_suffix' => 'nullable|string|max:100',
            'features' => 'required|array',
            'features.*' => 'required|string|max:255',
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
        ]);
        FacebookAdsPricingPackage::create($request->all());
        return redirect()->route('facebookAds.pricing-package.index')->with('success','Package created successfully!');
    }
    public function show($id): JsonResponse {
        return response()->json(FacebookAdsPricingPackage::findOrFail($id));
    }
    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:facebook_ads_pricing_categories,id',
            'title' => 'required|string|max:255',
            'price' => 'required|string|max:100',
            'price_suffix' => 'nullable|string|max:100',
            'features' => 'required|array',
            'features.*' => 'required|string|max:255',
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        FacebookAdsPricingPackage::findOrFail($id)->update($request->all());
        return redirect()->route('facebookAds.pricing-package.index')->with('success', 'Package updated successfully');
    }
    public function updateOrder(Request $request): JsonResponse { 
        $request->validate(['itemIds' => 'required|array']);
        foreach ($request->itemIds as $index => $id) {
            FacebookAdsPricingPackage::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
    }
    public function destroy($id): RedirectResponse {
        FacebookAdsPricingPackage::findOrFail($id)->delete();
        DB::statement('SET @count = 0;');
        DB::update('UPDATE facebook_ads_pricing_packages SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
        return redirect()->route('facebookAds.pricing-package.index')->with('success', 'Package deleted successfully.');
    }
}
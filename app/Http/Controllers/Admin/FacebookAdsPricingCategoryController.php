<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacebookAdsPricingCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FacebookAdsPricingCategoryController extends Controller
{
    public function __construct() {
         $this->middleware('permission:facebookAdsPricingCategoryView|facebookAdsPricingCategoryAdd|facebookAdsPricingCategoryUpdate|facebookAdsPricingCategoryDelete', ['only' => ['index','data']]);
         $this->middleware('permission:facebookAdsPricingCategoryAdd', ['only' => ['store']]);
         $this->middleware('permission:facebookAdsPricingCategoryUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:facebookAdsPricingCategoryDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? FacebookAdsPricingCategory::orderBy('order', 'asc')->get() : [];
        return view('admin.facebook_ads_pricing_category.index', compact('activeTab', 'items'));
    }
    public function data(Request $request): JsonResponse {
        $query = FacebookAdsPricingCategory::query();
        if ($request->filled('search')) { $query->where('name', 'like', '%' . $request->search . '%'); }
        $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
        return response()->json($query->paginate(10));
    }
    public function store(Request $request): RedirectResponse {
        $request->validate(['name' => 'required|string|max:255|unique:facebook_ads_pricing_categories,name']);
        FacebookAdsPricingCategory::create(['name' => $request->name]);
        return redirect()->route('facebookAds.pricingCategory.index')->with('success','Category created successfully!');
    }
    public function show($id): JsonResponse {
        return response()->json(FacebookAdsPricingCategory::findOrFail($id));
    }
    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), ['name' => 'required|string|max:255|unique:facebook_ads_pricing_categories,name,' . $id]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        FacebookAdsPricingCategory::findOrFail($id)->update(['name' => $request->name]);
        return redirect()->route('facebookAds.pricingCategory.index')->with('success', 'Category updated successfully');
    }
    public function updateOrder(Request $request): JsonResponse { 
        $request->validate(['itemIds' => 'required|array']);
        foreach ($request->itemIds as $index => $id) {
            FacebookAdsPricingCategory::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
    }
    public function destroy($id): RedirectResponse {
        // Deleting a category will also delete all packages in it (due to onDelete('cascade'))
        FacebookAdsPricingCategory::findOrFail($id)->delete();
        DB::statement('SET @count = 0;');
        DB::update('UPDATE facebook_ads_pricing_categories SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
        return redirect()->route('facebookAds.pricingCategory.index')->with('success', 'Category deleted successfully.');
    }
}
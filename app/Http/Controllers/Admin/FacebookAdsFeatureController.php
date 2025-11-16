<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacebookAdsFeature;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FacebookAdsFeatureController extends Controller
{
    public function __construct() {
         $this->middleware('permission:facebookAdsFeatureView|facebookAdsFeatureAdd|facebookAdsFeatureUpdate|facebookAdsFeatureDelete', ['only' => ['index','data']]);
         $this->middleware('permission:facebookAdsFeatureAdd', ['only' => ['store']]);
         $this->middleware('permission:facebookAdsFeatureUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:facebookAdsFeatureDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? FacebookAdsFeature::orderBy('order', 'asc')->get() : [];
        return view('admin.facebook_ads_feature.index', compact('activeTab', 'items'));
    }
    public function data(Request $request): JsonResponse {
        $query = FacebookAdsFeature::query();
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
        return response()->json($query->paginate(10));
    }
    public function store(Request $request): RedirectResponse {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon_name' => 'required|string|max:100',
        ]);
        FacebookAdsFeature::create($request->all());
        return redirect()->route('facebookAds.feature.index')->with('success','Feature created successfully!');
    }
    public function show($id): JsonResponse {
        return response()->json(FacebookAdsFeature::findOrFail($id));
    }
    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon_name' => 'required|string|max:100',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        FacebookAdsFeature::findOrFail($id)->update($request->all());
        return redirect()->route('facebookAds.feature.index')->with('success', 'Feature updated successfully');
    }
    public function updateOrder(Request $request): JsonResponse { 
        $request->validate(['itemIds' => 'required|array']);
        foreach ($request->itemIds as $index => $id) {
            FacebookAdsFeature::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
    }
    public function destroy($id): RedirectResponse {
        FacebookAdsFeature::findOrFail($id)->delete();
        DB::statement('SET @count = 0;');
        DB::update('UPDATE facebook_ads_features SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
        return redirect()->route('facebookAds.feature.index')->with('success', 'Feature deleted successfully.');
    }
}
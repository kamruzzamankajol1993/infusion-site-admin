<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacebookAdsCampaign;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FacebookAdsCampaignController extends Controller
{
    public function __construct() {
         $this->middleware('permission:facebookAdsCampaignView|facebookAdsCampaignAdd|facebookAdsCampaignUpdate|facebookAdsCampaignDelete', ['only' => ['index','data']]);
         $this->middleware('permission:facebookAdsCampaignAdd', ['only' => ['store']]);
         $this->middleware('permission:facebookAdsCampaignUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:facebookAdsCampaignDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? FacebookAdsCampaign::orderBy('order', 'asc')->get() : [];
        return view('admin.facebook_ads_campaign.index', compact('activeTab', 'items'));
    }
    public function data(Request $request): JsonResponse {
        $query = FacebookAdsCampaign::query();
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
        return response()->json($query->paginate(10));
    }
    public function store(Request $request): RedirectResponse {
        $request->validate(['title' => 'required|string|max:255', 'description' => 'required|string']);
        FacebookAdsCampaign::create($request->all());
        return redirect()->route('facebookAds.campaign.index')->with('success','Campaign created successfully!');
    }
    public function show($id): JsonResponse {
        return response()->json(FacebookAdsCampaign::findOrFail($id));
    }
    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), ['title' => 'required|string|max:255', 'description' => 'required|string']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        FacebookAdsCampaign::findOrFail($id)->update($request->all());
        return redirect()->route('facebookAds.campaign.index')->with('success', 'Campaign updated successfully');
    }
    public function updateOrder(Request $request): JsonResponse { 
        $request->validate(['itemIds' => 'required|array']);
        foreach ($request->itemIds as $index => $id) {
            FacebookAdsCampaign::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
    }
    public function destroy($id): RedirectResponse {
        FacebookAdsCampaign::findOrFail($id)->delete();
        DB::statement('SET @count = 0;');
        DB::update('UPDATE facebook_ads_campaigns SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
        return redirect()->route('facebookAds.campaign.index')->with('success', 'Campaign deleted successfully.');
    }
}
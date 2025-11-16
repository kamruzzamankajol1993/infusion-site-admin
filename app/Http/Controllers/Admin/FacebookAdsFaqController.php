<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacebookAdsFaq;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FacebookAdsFaqController extends Controller
{
    public function __construct() {
         $this->middleware('permission:facebookAdsFaqView|facebookAdsFaqAdd|facebookAdsFaqUpdate|facebookAdsFaqDelete', ['only' => ['index','data']]);
         $this->middleware('permission:facebookAdsFaqAdd', ['only' => ['store']]);
         $this->middleware('permission:facebookAdsFaqUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:facebookAdsFaqDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? FacebookAdsFaq::orderBy('order', 'asc')->get() : [];
        return view('admin.facebook_ads_faq.index', compact('activeTab', 'items'));
    }
    public function data(Request $request): JsonResponse {
        $query = FacebookAdsFaq::query();
        if ($request->filled('search')) {
            $query->where('question', 'like', '%' . $request->search . '%');
        }
        $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
        return response()->json($query->paginate(10));
    }
    public function store(Request $request): RedirectResponse {
        $request->validate(['question' => 'required|string|max:255', 'answer' => 'required|string']);
        FacebookAdsFaq::create($request->all());
        return redirect()->route('facebookAds.faq.index')->with('success','FAQ created successfully!');
    }
    public function show($id): JsonResponse {
        return response()->json(FacebookAdsFaq::findOrFail($id));
    }
    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), ['question' => 'required|string|max:255', 'answer' => 'required|string']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        FacebookAdsFaq::findOrFail($id)->update($request->all());
        return redirect()->route('facebookAds.faq.index')->with('success', 'FAQ updated successfully');
    }
    public function updateOrder(Request $request): JsonResponse { 
        $request->validate(['itemIds' => 'required|array']);
        foreach ($request->itemIds as $index => $id) {
            FacebookAdsFaq::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
    }
    public function destroy($id): RedirectResponse {
        FacebookAdsFaq::findOrFail($id)->delete();
        DB::statement('SET @count = 0;');
        DB::update('UPDATE facebook_ads_faqs SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
        return redirect()->route('facebookAds.faq.index')->with('success', 'FAQ deleted successfully.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreMainBanner;
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

class StoreMainBannerController extends Controller
{
    use ImageUploadTrait;

    public function __construct() {
         $this->middleware('permission:storeMainBannerView|storeMainBannerAdd|storeMainBannerUpdate|storeMainBannerDelete', ['only' => ['index','data']]);
         $this->middleware('permission:storeMainBannerAdd', ['only' => ['store']]);
         $this->middleware('permission:storeMainBannerUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:storeMainBannerDelete', ['only' => ['destroy']]);
    }

    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? StoreMainBanner::orderBy('order', 'asc')->get() : [];
        return view('admin.store_main_banner.index', compact('activeTab', 'items'));
    }

    public function data(Request $request): JsonResponse {
        try {
            $query = StoreMainBanner::query();
            if ($request->filled('search')) {
                $query->where('link', 'like', '%' . $request->search . '%');
            }
            $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
            $paginated = $query->paginate(10);
            return response()->json($paginated);
        } catch (Exception $e) { return response()->json(['error' => 'Failed to retrieve data.'], 500); }
    }

    public function store(Request $request): RedirectResponse {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // 800x424
            'link' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);
        try {
            $data = $request->only('link', 'status');
            $data['image'] = $this->handleImageUpload($request, new StoreMainBanner(), 'image', 'store_banners', 800, 424);
            if (!$data['image']) throw new Exception("Image upload failed.");
            StoreMainBanner::create($data);
            return redirect()->route('storeMainBanner.index')->with('success','Banner created successfully!');
        } catch (Exception $e) { return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]); }
    }

    public function show($id): JsonResponse {
        $item = StoreMainBanner::findOrFail($id);
        if ($item->image) $item->image_url = asset($item->image);
        return response()->json($item);
    }

    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 800x424
            'link' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        try {
            $item = StoreMainBanner::findOrFail($id);
            $data = $request->only('link', 'status');
            if ($request->hasFile('image')) {
                $data['image'] = $this->handleImageUpdate($request, $item, 'image', 'store_banners', 800, 424);
            }
            $item->update($data);
            return redirect()->route('storeMainBanner.index')->with('success', 'Banner updated successfully');
        } catch (Exception $e) {
             return redirect()->back()->withErrors(['error' => 'Failed to update banner.'], 'update')->withInput()->with('error_modal_id', $id);
        }
    }

    public function updateOrder(Request $request): JsonResponse { 
        $request->validate(['itemIds' => 'required|array']);
        try {
            foreach ($request->itemIds as $index => $id) {
                StoreMainBanner::where('id', $id)->update(['order' => $index + 1]);
            }
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
        } catch (Exception $e) { return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500); }
    }

    public function destroy($id): RedirectResponse {
        try {
            $item = StoreMainBanner::findOrFail($id);
            if ($item->image && File::exists(public_path($item->image))) File::delete(public_path($item->image));
            $item->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE store_main_banners SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('storeMainBanner.index')->with('success', 'Banner deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('storeMainBanner.index')->with('error', 'Failed to delete banner.');
        }
    }
}
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacebookAdsFeature;
use App\Traits\ImageUploadTrait; // Import Trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class FacebookAdsFeatureController extends Controller
{
    use ImageUploadTrait; // Use Trait

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
            // Image validation
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:256', // 80x80
        ]);

        try {
            $data = $request->only('title', 'description');
            
            // Handle Image Upload
            $tempModel = new FacebookAdsFeature();
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'facebook_ads_features', 80, 80);
            
            if ($imagePath) {
                $data['image'] = $imagePath;
            } else {
                throw new Exception("Image upload failed.");
            }

            FacebookAdsFeature::create($data);
            return redirect()->route('facebookAds.feature.index')->with('success','Feature created successfully!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id): JsonResponse {
        $item = FacebookAdsFeature::findOrFail($id);
        if ($item->image) $item->image_url = asset($item->image);
        return response()->json($item);
    }

    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:256', // 80x80
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }

        try {
            $item = FacebookAdsFeature::findOrFail($id);
            $data = $request->only('title', 'description');

            if ($request->hasFile('image')) {
                $data['image'] = $this->handleImageUpdate($request, $item, 'image', 'facebook_ads_features', 80, 80);
            }

            $item->update($data);
            return redirect()->route('facebookAds.feature.index')->with('success', 'Feature updated successfully');
        } catch (Exception $e) {
             return redirect()->back()->withErrors(['error' => 'Failed to update feature.'], 'update')->withInput()->with('error_modal_id', $id);
        }
    }

    public function updateOrder(Request $request): JsonResponse { 
        $request->validate(['itemIds' => 'required|array']);
        foreach ($request->itemIds as $index => $id) {
            FacebookAdsFeature::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
    }

    public function destroy($id): RedirectResponse {
        try {
            $item = FacebookAdsFeature::findOrFail($id);
            
            // Delete image file
            if ($item->image && File::exists(public_path($item->image))) {
                File::delete(public_path($item->image));
            }

            $item->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE facebook_ads_features SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('facebookAds.feature.index')->with('success', 'Feature deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('facebookAds.feature.index')->with('error', 'Failed to delete feature.');
        }
    }
}
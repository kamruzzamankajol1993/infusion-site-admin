<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacebookMoreService;
use App\Traits\ImageUploadTrait; // Import Trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class FacebookMoreServiceController extends Controller
{
    use ImageUploadTrait; // Use Trait

    public function __construct() {
         $this->middleware('permission:facebookMoreServiceView|facebookMoreServiceAdd|facebookMoreServiceUpdate|facebookMoreServiceDelete', ['only' => ['index','data']]);
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
            'link_text' => 'required|string|max:100',
            'link_url' => 'required|string|max:255',
            // Image validation
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:256', // 80x80
        ]);

        try {
            $data = $request->only('title', 'description', 'link_text', 'link_url');
            
            // Handle Image Upload
            $tempModel = new FacebookMoreService();
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'facebook_more_services', 80, 80);
            
            if ($imagePath) {
                $data['image'] = $imagePath;
            } else {
                throw new Exception("Image upload failed.");
            }

            FacebookMoreService::create($data);
            return redirect()->route('facebookPage.service.index')->with('success','Service created successfully!');
        } catch (Exception $e) { return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]); }
    }

    public function show($id): JsonResponse {
        try {
            $item = FacebookMoreService::findOrFail($id);
            if ($item->image) $item->image_url = asset($item->image);
            return response()->json($item);
        } catch (Exception $e) { return response()->json(['error' => 'Service not found.'], 404); }
    }

    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'link_text' => 'required|string|max:100',
            'link_url' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:256',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }

        try {
            $item = FacebookMoreService::findOrFail($id);
            $data = $request->only('title', 'description', 'link_text', 'link_url');

            // Handle Image Update
            if ($request->hasFile('image')) {
                $imagePath = $this->handleImageUpdate($request, $item, 'image', 'facebook_more_services', 80, 80);
                $data['image'] = $imagePath;
            }

            $item->update($data);
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
            $item = FacebookMoreService::findOrFail($id);
            
            // Delete image file
            if ($item->image && File::exists(public_path($item->image))) {
                File::delete(public_path($item->image));
            }

            $item->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE facebook_more_services SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('facebookPage.service.index')->with('success', 'Service deleted successfully.');
        } catch (Exception $e) { return redirect()->route('facebookPage.service.index')->with('error', 'Failed to delete service.'); }
    }
}
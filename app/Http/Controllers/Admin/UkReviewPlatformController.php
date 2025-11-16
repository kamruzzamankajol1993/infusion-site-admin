<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UkReviewPlatform;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class UkReviewPlatformController extends Controller
{
    use ImageUploadTrait;
    public function __construct() {
         $this->middleware('permission:ukReviewPlatformView|ukReviewPlatformAdd|ukReviewPlatformUpdate|ukReviewPlatformDelete', ['only' => ['index','data']]);
         $this->middleware('permission:ukReviewPlatformAdd', ['only' => ['store']]);
         $this->middleware('permission:ukReviewPlatformUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:ukReviewPlatformDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? UkReviewPlatform::orderBy('order', 'asc')->get() : [];
        return view('admin.uk_review_platform.index', compact('activeTab', 'items'));
    }
    public function data(Request $request): JsonResponse {
        $query = UkReviewPlatform::query();
        if ($request->filled('search')) { $query->where('name', 'like', '%' . $request->search . '%'); }
        $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
        return response()->json($query->paginate(10));
    }
    public function store(Request $request): RedirectResponse {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:256', // 200x60
            'rating_text' => 'required|string|max:255',
            'review_link' => 'required|string|max:255',
        ]);
        try {
            $data = $request->only('name', 'rating_text', 'review_link');
            $data['image'] = $this->handleImageUpload($request, new UkReviewPlatform(), 'image', 'uk_reviews', 200, 60);
            if (!$data['image']) throw new Exception("Image upload failed.");
            UkReviewPlatform::create($data);
            return redirect()->route('ukCompany.review-platform.index')->with('success','Platform created successfully!');
        } catch (Exception $e) { return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]); }
    }
    public function show($id): JsonResponse {
        $item = UkReviewPlatform::findOrFail($id);
        if ($item->image) $item->image_url = asset($item->image);
        return response()->json($item);
    }
    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:256', // 200x60
            'rating_text' => 'required|string|max:255',
            'review_link' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        try {
            $item = UkReviewPlatform::findOrFail($id);
            $data = $request->only('name', 'rating_text', 'review_link');
            if ($request->hasFile('image')) {
                $data['image'] = $this->handleImageUpdate($request, $item, 'image', 'uk_reviews', 200, 60);
            }
            $item->update($data);
            return redirect()->route('ukCompany.review-platform.index')->with('success', 'Platform updated successfully');
        } catch (Exception $e) {
             return redirect()->back()->withErrors(['error' => 'Failed to update platform.'], 'update')->withInput()->with('error_modal_id', $id);
        }
    }
    public function updateOrder(Request $request): JsonResponse { /* ... same as previous ... */ 
        $request->validate(['itemIds' => 'required|array']);
        foreach ($request->itemIds as $index => $id) {
            UkReviewPlatform::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
    }
    public function destroy($id): RedirectResponse {
        try {
            $item = UkReviewPlatform::findOrFail($id);
            if ($item->image && File::exists(public_path($item->image))) File::delete(public_path($item->image));
            $item->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE uk_review_platforms SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('ukCompany.review-platform.index')->with('success', 'Platform deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('ukCompany.review-platform.index')->with('error', 'Failed to delete platform.');
        }
    }
}
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UkTestimonial;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class UkTestimonialController extends Controller
{
    use ImageUploadTrait;
    public function __construct() {
         $this->middleware('permission:ukTestimonialView|ukTestimonialAdd|ukTestimonialUpdate|ukTestimonialDelete', ['only' => ['index','data']]);
         $this->middleware('permission:ukTestimonialAdd', ['only' => ['store']]);
         $this->middleware('permission:ukTestimonialUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:ukTestimonialDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? UkTestimonial::orderBy('order', 'asc')->get() : [];
        return view('admin.uk_testimonial.index', compact('activeTab', 'items'));
    }
    public function data(Request $request): JsonResponse {
        $query = UkTestimonial::query();
        if ($request->filled('search')) { $query->where('name', 'like', '%' . $request->search . '%'); }
        $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
        return response()->json($query->paginate(10));
    }
    public function store(Request $request): RedirectResponse {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'quote' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:512', // 100x100
        ]);
        try {
            $data = $request->only('name', 'designation', 'quote', 'rating');
            if ($request->hasFile('image')) {
                $data['image'] = $this->handleImageUpload($request, new UkTestimonial(), 'image', 'uk_testimonials', 100, 100);
            }
            UkTestimonial::create($data);
            return redirect()->route('ukCompany.testimonial.index')->with('success','Testimonial created successfully!');
        } catch (Exception $e) { return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]); }
    }
    public function show($id): JsonResponse {
        $item = UkTestimonial::findOrFail($id);
        if ($item->image) $item->image_url = asset($item->image);
        return response()->json($item);
    }
    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'quote' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:512', // 100x100
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        try {
            $item = UkTestimonial::findOrFail($id);
            $data = $request->only('name', 'designation', 'quote', 'rating');
            if ($request->hasFile('image')) {
                $data['image'] = $this->handleImageUpdate($request, $item, 'image', 'uk_testimonials', 100, 100);
            }
            $item->update($data);
            return redirect()->route('ukCompany.testimonial.index')->with('success', 'Testimonial updated successfully');
        } catch (Exception $e) {
             return redirect()->back()->withErrors(['error' => 'Failed to update testimonial.'], 'update')->withInput()->with('error_modal_id', $id);
        }
    }
    public function updateOrder(Request $request): JsonResponse { /* ... same as previous ... */ 
        $request->validate(['itemIds' => 'required|array']);
        foreach ($request->itemIds as $index => $id) {
            UkTestimonial::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
    }
    public function destroy($id): RedirectResponse {
        try {
            $item = UkTestimonial::findOrFail($id);
            if ($item->image && File::exists(public_path($item->image))) File::delete(public_path($item->image));
            $item->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE uk_testimonials SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('ukCompany.testimonial.index')->with('success', 'Testimonial deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('ukCompany.testimonial.index')->with('error', 'Failed to delete testimonial.');
        }
    }
}
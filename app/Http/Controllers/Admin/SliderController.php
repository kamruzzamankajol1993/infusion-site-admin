<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Traits\ImageUploadTrait; // Import trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Exception;

class SliderController extends Controller
{
    use ImageUploadTrait; // Use the trait

    /**
     * Permissions middleware.
     */
    public function __construct()
    {
        // Adjust permission names
        $this->middleware('permission:sliderView', ['only' => ['index', 'data', 'show']]);
        $this->middleware('permission:sliderAdd', ['only' => ['create', 'store']]);
        $this->middleware('permission:sliderUpdate', ['only' => ['edit', 'update','updateOrder', 'allForReorder']]);
        $this->middleware('permission:sliderDelete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.slider.index');
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = Slider::query();

            // Search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('subtitle', 'like', '%' . $searchTerm . '%')
                      ->orWhere('short_description', 'like', '%' . $searchTerm . '%');
            }

            // Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            $allowedSorts = ['id', 'title', 'subtitle', 'created_at'];
            
                $query->orderBy('id', 'asc'); // Fallback
            

            $paginated = $query->paginate(10); // Adjust page size

            // Add image_url using accessor
            $paginated->getCollection()->transform(fn($item) => $item); // Trigger accessor

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch sliders: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve sliders.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Title, Subtitle, Description are nullable based on migration/model
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB example
        ],[
            'image.dimensions' => 'Image dimensions must be exactly 1920x1080 pixels.',
            'image.max' => 'Image size cannot exceed 5MB.',
        ]);

        DB::beginTransaction();
        try {
            $sliderData = $validatedData;
            unset($sliderData['image']); // Remove image for initial data

            // Handle Image Upload using Trait
            $tempModel = new Slider();
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'sliders', 1920, 1080); // Pass dimensions
            if ($imagePath) {
                $sliderData['image'] = $imagePath; // Path relative to public/uploads/
            } else {
                 throw new Exception("Slider image upload failed or missing.");
            }

            Slider::create($sliderData);
            DB::commit();

            Log::info('Slider created successfully.', ['title' => $request->title ?? 'Untitled']);
            return redirect()->route('slider.index')->with('success', 'Slider created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create slider: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to save slider. Please check logs.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider): View
    {
         return view('admin.slider.show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider): View
    {
        return view('admin.slider.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $validatedData = $request->validate([
             'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Nullable on update
        ],[
            'image.dimensions' => 'Image dimensions must be exactly 1920x 1080 pixels.',
            'image.max' => 'Image size cannot exceed 5MB.',
        ]);

        DB::beginTransaction();
        try {
            $sliderData = $validatedData;
            unset($sliderData['image']); // Remove image initially

            // Handle Image Update using Trait
            $imagePath = $this->handleImageUpdate($request, $slider, 'image', 'sliders', 1920, 1080); // Pass dimensions
            $sliderData['image'] = $imagePath; // Trait returns old or new path

            $slider->update($sliderData);
            DB::commit();

            Log::info('Slider updated successfully.', ['id' => $slider->id]);
            return redirect()->route('slider.index')->with('success', 'Slider updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update slider ID ' . $slider->id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to update slider. Please check logs.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        try {
            DB::beginTransaction();

            // Delete Image file
            $imageFullPath = public_path($slider->image); // Assuming trait saves relative to public/
             if ($slider->image && File::exists($imageFullPath)) {
                 File::delete($imageFullPath);
             }

            $slider->delete();
            DB::commit();

            // 4. RETURN REDIRECT WITH SUCCESS
            return redirect()->route('slider.index')->with('success', 'Slider deleted successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete slider ID {$slider->id}: " . $e->getMessage());
            
            // 5. RETURN REDIRECT WITH ERROR
            return redirect()->route('slider.index')->with('error', 'Failed to delete slider.');
        }
    }

    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:sliders,id',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->order as $index => $id) {
                Slider::where('id', $id)->update(['display_order' => $index + 1]);
            }
            DB::commit();
            return response()->json(['success' => 'Slider order updated successfully.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update slider order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update order.'], 500);
        }
    }


    // --- 2. NEW METHOD ---
    /**
     * Fetch all sliders for reordering.
     */
    public function allForReorder(): JsonResponse
    {
        try {
            // Fetch all sliders, ordered by their current display_order
            $sliders = Slider::orderBy('display_order', 'asc')->get();
            
            // Trigger accessor to get full image URLs
            $sliders->transform(fn($item) => $item); 
            
            return response()->json($sliders);

        } catch (Exception $e) {
            Log::error('Failed to fetch all sliders for reorder: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve sliders.'], 500);
        }
    }
    // --- END NEW METHOD ---

    
}
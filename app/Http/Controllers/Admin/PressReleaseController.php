<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PressRelease;
use App\Traits\ImageUploadTrait; // Import trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Validation\Rule; // For conditional validation
use Illuminate\Support\Str;
class PressReleaseController extends Controller
{
    use ImageUploadTrait; // Use the trait

    /**
     * Permissions middleware.
     */
    public function __construct()
    {
        // Adjust permission names
        $this->middleware('permission:pressReleaseView', ['only' => ['index', 'data', 'show']]);
        $this->middleware('permission:pressReleaseAdd', ['only' => ['create', 'store']]);
        $this->middleware('permission:pressReleaseUpdate', ['only' => ['edit', 'update']]);
        $this->middleware('permission:pressReleaseDelete', ['only' => ['destroy']]);
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;
        $query = PressRelease::where('slug', $slug);
        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            $query = PressRelease::where('slug', $slug); // Reset query for next loop
            if ($ignoreId !== null) {
                $query->where('id', '!=', $ignoreId);
            }
        }
        return $slug;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.press_release.index');
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = PressRelease::query();

            // Search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%')
                      ->orWhere('link', 'like', '%' . $searchTerm . '%');
            }

            // Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            $allowedSorts = ['id', 'title', 'type', 'created_at'];
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('id', 'desc'); // Fallback
            }

            $paginated = $query->paginate(10); // Adjust page size

            // Add image_url using accessor
            $paginated->getCollection()->transform(fn($item) => $item); // Trigger accessor if needed

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch press releases: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve items.'], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // $types = ['link' => 'Link', 'description' => 'Description']; // REMOVED
        return view('admin.press_release.create'); // Removed compact
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:press_releases,title',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'release_date' => 'nullable|date_format:Y-m-d',
            // 'type' => ['required', Rule::in(['link', 'description'])], // REMOVED
            'link' => 'nullable|url|max:1000', // Now nullable
            'description' => 'nullable|string', // Now nullable
        ],[
            // 'link.required' => '...', // REMOVED
            // 'description.required' => '...', // REMOVED
            'image.dimensions' => 'Image dimensions must be exactly 1200x800 pixels.',
        ]);

        DB::beginTransaction();
        try {
            $pressData = $validatedData;
            unset($pressData['image']);

            // Add Slug
            $pressData['slug'] = $this->generateUniqueSlug($request->title);

            // Handle Image Upload
            $tempModel = new PressRelease();
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'press_releases', 1200, 800);
            if ($imagePath) {
                $pressData['image'] = $imagePath;
            } else { throw new Exception("Image upload failed or missing."); }

            // REMOVED logic to null fields based on type

            PressRelease::create($pressData);
            DB::commit();

            Log::info('Press Release created successfully.', ['title' => $request->title]);
            return redirect()->route('pressRelease.index')->with('success', 'Press Release created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create press release: ' . $e->getMessage());
             return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to save item. Please check logs.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PressRelease $pressRelease): View
    {
         // Route model binding finds the model automatically
         return view('admin.press_release.show', ['pressRelease' => $pressRelease]); // Pass using key 'pressRelease'
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PressRelease $pressRelease): View
    {
        // $types = ['link' => 'Link', 'description' => 'Description']; // REMOVED
        return view('admin.press_release.edit', ['pressRelease' => $pressRelease]); // Removed types
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PressRelease $pressRelease): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:press_releases,title,' . $pressRelease->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'release_date' => 'nullable|date_format:Y-m-d',
            // 'type' => ['required', Rule::in(['link', 'description'])], // REMOVED
            'link' => 'nullable|url|max:1000', // Now nullable
            'description' => 'nullable|string', // Now nullable
        ],[
            // 'link.required' => '...', // REMOVED
            // 'description.required' => '...', // REMOVED
            'image.dimensions' => 'Image dimensions must be exactly 1200x800 pixels.',
        ]);

        DB::beginTransaction();
        try {
            $pressData = $validatedData;
            unset($pressData['image']);

            // Update Slug ONLY if title changed
           
                $pressData['slug'] = $this->generateUniqueSlug($request->title, $pressRelease->id);
         

            // Handle Image Update
            $imagePath = $this->handleImageUpdate($request, $pressRelease, 'image', 'press_releases', 1200, 800);
            $pressData['image'] = $imagePath;

            // REMOVED logic to null fields based on type

            $pressRelease->update($pressData);
            DB::commit();

            Log::info('Press Release updated successfully.', ['id' => $pressRelease->id]);
            return redirect()->route('pressRelease.index')->with('success', 'Press Release updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update press release ID ' . $pressRelease->id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to update item. Please check logs.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     * --- 2. UPDATED THIS METHOD ---
     */
    public function destroy(PressRelease $pressRelease): RedirectResponse // <-- 3. CHANGED return type
    {
         // Route model binding finds the model automatically
        try {
            DB::beginTransaction();

            // Delete Image file
            $imageFullPath = public_path($pressRelease->image);
            if ($pressRelease->image && File::exists($imageFullPath)) {
                 File::delete($imageFullPath);
             }

            $pressRelease->delete();
            DB::commit();

            // 4. RETURN REDIRECT WITH SUCCESS
            return redirect()->route('pressRelease.index')->with('success', 'Press Release deleted successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete press release ID {$pressRelease->id}: " . $e->getMessage());
            
            // 5. RETURN REDIRECT WITH ERROR
            return redirect()->route('pressRelease.index')->with('error', 'Failed to delete item.');
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Traits\ImageUploadTrait; // Import trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // For file operations
use Exception;
use Illuminate\Validation\Rule; // For conditional validation

class GalleryController extends Controller
{
    use ImageUploadTrait; // Use the trait

    /**
     * Permissions middleware.
     */
    public function __construct()
    {
        // Adjust permission names
        $this->middleware('permission:galleryView', ['only' => ['index', 'data', 'show']]);
        $this->middleware('permission:galleryAdd', ['only' => ['create', 'store']]);
        $this->middleware('permission:galleryUpdate', ['only' => ['edit', 'update']]);
        $this->middleware('permission:galleryDelete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.gallery.index');
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = Gallery::query();

            // Search
            if ($request->filled('search')) {
                $query->where('short_description', 'like', '%' . $request->search . '%')
                      ->orWhere('youtube_link', 'like', '%' . $request->search . '%');
            }

            // Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            $allowedSorts = ['id', 'type', 'created_at']; // Add 'short_description' if needed
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('id', 'desc'); // Fallback
            }

            $paginated = $query->paginate(10); // Adjust page size

            // Add URLs using accessors defined in the model
            $paginated->getCollection()->transform(function ($item) {
                // Accessors automatically add these if accessed
                // $item->image_url = $item->image_url;
                // $item->video_thumbnail_url = $item->video_thumbnail_url;
                return $item;
            });

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch gallery items: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve gallery items.'], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $types = ['image', 'video']; // Define types
        return view('admin.gallery.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'short_description' => 'nullable|string|max:500',
            'type' => ['required', Rule::in(['image', 'video'])],
            // Conditional validation based on 'type'
            'image_file' => [
                Rule::requiredIf($request->input('type') === 'image'), // Required only if type is 'image'
                'nullable', // Allow null otherwise
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // Max 2MB example
     
            ],
            'youtube_link' => [
                Rule::requiredIf($request->input('type') === 'video'), // Required only if type is 'video'
                'nullable', // Allow null otherwise
                'url',
                'regex:/^(https?:\\/\\/)?(www\\.)?(youtube\\.com\\/watch\\?v=|youtu\\.be\\/)([a-zA-Z0-9_-]{11})(&.*)?$/' // Basic YouTube URL regex
            ],
        ], [
            // Custom messages for conditional rules
            'image_file.required' => 'The image file is required when type is Image.',
            'youtube_link.required' => 'The YouTube link is required when type is Video.',
            'youtube_link.regex' => 'Please enter a valid YouTube video URL.',
        ]);

        DB::beginTransaction();
        try {
            $galleryData = $validatedData;

            // Unset the file/link that isn't needed based on type
            if ($galleryData['type'] === 'image') {
                unset($galleryData['youtube_link']);
                unset($galleryData['image_file']); // Let trait handle it

                // Handle Image Upload
                $tempModel = new Gallery();
                $imagePath = $this->handleImageUpload($request, $tempModel, 'image_file', 'gallery', 1500, 990);
                if ($imagePath) {
                    $galleryData['image_file'] = $imagePath;
                } else {
                    throw new Exception("Image upload failed or missing.");
                }
                 $galleryData['youtube_link'] = null; // Ensure link is null for images

            } elseif ($galleryData['type'] === 'video') {
                unset($galleryData['image_file']); // Remove image field from data
                 $galleryData['image_file'] = null; // Ensure image is null for videos
            }

            Gallery::create($galleryData);
            DB::commit();

            Log::info('Gallery item created successfully.');
            return redirect()->route('gallery.index')->with('success', 'Gallery item created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create gallery item: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to save gallery item. Please check logs.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery): View
    {
         return view('admin.gallery.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery): View
    {
        $types = ['image', 'video'];
        return view('admin.gallery.edit', compact('gallery', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $validatedData = $request->validate([
            'short_description' => 'nullable|string|max:500',
            'type' => ['required', Rule::in(['image', 'video'])],
            'image_file' => [
                Rule::requiredIf(function () use ($request, $gallery) {
                    // Required if type is image AND no image currently exists
                    return $request->input('type') === 'image' && !$gallery->image_file;
                }),
                'nullable', // Allow null otherwise or if keeping existing
                'image','mimes:jpeg,png,jpg,gif,webp','max:2048',
             
            ],
            'youtube_link' => [
                Rule::requiredIf($request->input('type') === 'video'),
                'nullable','url',
                'regex:/^(https?:\\/\\/)?(www\\.)?(youtube\\.com\\/watch\\?v=|youtu\\.be\\/)([a-zA-Z0-9_-]{11})(&.*)?$/'
            ],
        ], [
            'image_file.required' => 'The image file is required when type is Image and no image exists.',
            'youtube_link.required' => 'The YouTube link is required when type is Video.',
             'youtube_link.regex' => 'Please enter a valid YouTube video URL.',
        ]);

        DB::beginTransaction();
        try {
            $galleryData = $validatedData;

            if ($galleryData['type'] === 'image') {
                // Handle Image Update (keeps old if no new file)
                $imagePath = $this->handleImageUpdate($request, $gallery, 'image_file', 'gallery', 1500, 990);
                $galleryData['image_file'] = $imagePath;
                // If type changed to image, or was already image, clear youtube link
                 $galleryData['youtube_link'] = null;

            } elseif ($galleryData['type'] === 'video') {
                // If type changed to video, delete old image file
                 if ($gallery->image_file && $gallery->type === 'image') { // Check if previous type was image
                    if (File::exists(public_path('uploads/' . $gallery->image_file))) {
                         File::delete(public_path('uploads/' . $gallery->image_file));
                     }
                      // Or adjust path if trait saves differently
                      // if (File::exists(public_path($gallery->image_file))) { File::delete(public_path($gallery->image_file)); }
                 }
                 // Clear image path in database
                 $galleryData['image_file'] = null;
                 // Keep the validated youtube link (already in $galleryData)
            }

            $gallery->update($galleryData);
            DB::commit();

            Log::info('Gallery item updated successfully.', ['id' => $gallery->id]);
            return redirect()->route('gallery.index')->with('success', 'Gallery item updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update gallery item ID ' . $gallery->id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to update gallery item. Please check logs.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     * --- UPDATED TO RETURN REDIRECTRESPONSE ---
     */
    public function destroy(Gallery $gallery): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Delete associated Image file if it exists
            if ($gallery->type === 'image' && $gallery->image_file) {
                 // Adjust path based on how your trait saves (relative to public/uploads or public/)
                $imageFullPath = public_path('uploads/' . $gallery->image_file); // Assuming trait saves relative to public/uploads
                // Or: $imageFullPath = public_path($gallery->image_file); // If trait saves relative to public/
                 if (File::exists($imageFullPath)) {
                     File::delete($imageFullPath);
                 }
            }

            $gallery->delete();
            DB::commit();

            // --- CHANGED ---
            return redirect()->route('gallery.index')->with('success', 'Gallery item deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete gallery item ID {$gallery->id}: " . $e->getMessage());
            
            // --- CHANGED ---
            return redirect()->route('gallery.index')->with('error', 'Failed to delete gallery item.');
        }
    }
}
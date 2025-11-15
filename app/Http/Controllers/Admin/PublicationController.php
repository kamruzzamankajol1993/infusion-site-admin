<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publication; // Import the model
use App\Traits\ImageUploadTrait; // Import the trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // For file operations
use Exception;
use Intervention\Image\Laravel\Facades\Image;

class PublicationController extends Controller
{
    use ImageUploadTrait; // Use the image upload trait

    /**
     * Permissions middleware.
     */
    public function __construct()
    {
        // Adjust permission names as needed
        $this->middleware('permission:publicationView', ['only' => ['index', 'data', 'show']]);
        $this->middleware('permission:publicationAdd', ['only' => ['create', 'store']]);
        $this->middleware('permission:publicationUpdate', ['only' => ['edit', 'update']]);
        $this->middleware('permission:publicationDelete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.publication.index');
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = Publication::query();

            // Search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
            }

            // Sorting
            $sortColumn = $request->input('sort', 'date'); // Default sort
            $sortDirection = $request->input('direction', 'desc'); // Newest first
            $allowedSorts = ['id', 'title', 'date', 'created_at'];
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('date', 'desc'); // Fallback sort
            }

            $paginated = $query->paginate(10); // Adjust page size

            // Add pdf_url and image_url for easy access in JS/Blade
            $paginated->getCollection()->transform(function ($item) {
                // Assuming PDF is stored directly in public path relative structure
                $item->pdf_url = $item->pdf_file ? asset('public/'.$item->pdf_file) : null;
                // Assuming ImageUploadTrait stores relative to 'public/uploads'
                $item->image_url = $item->image ? asset('uploads/' . $item->image) : null;
                 // Or if ImageUploadTrait stores relative to public base path like PDF
                 // $item->image_url = $item->image ? asset($item->image) : null;
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
            Log::error('Failed to fetch publications: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve publications.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.publication.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:publications,title',
            'date' => 'required|date_format:Y-m-d',
            'pdf_file' => 'required|file|mimes:pdf|max:5120', // Max 5MB
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // Max 1MB example
        ]);

        DB::beginTransaction();
        try {
            $publicationData = $validatedData;
            // Remove file inputs initially
            unset($publicationData['pdf_file'], $publicationData['image']);

            // --- Handle PDF File Upload (Directly to Public) ---
            if ($request->hasFile('pdf_file')) {
                $file = $request->file('pdf_file');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/publications/pdfs'); // Subfolder for PDFs
                 // Ensure directory exists
                if (!File::isDirectory($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $file->move($destinationPath, $fileName);
                $publicationData['pdf_file'] = 'uploads/publications/pdfs/' . $fileName; // Store relative path
            } else {
                 // Should not happen due to 'required' validation, but good practice
                 throw new Exception("PDF file upload failed or missing.");
            }

            // --- Handle Image Upload using Trait ---
            $tempModel = new Publication(); // Temporary instance for trait context
            // Specify subfolder for images within 'public/uploads'
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'publications/images', 600, 400);
            if ($imagePath) {
                // The trait now returns the path relative to storage/app/public/uploads, adjust if needed based on trait implementation
                $publicationData['image'] = $imagePath; // Store path returned by trait
            } else {
                // Should not happen due to 'required' validation
                throw new Exception("Image upload failed or missing.");
            }

            // Create Publication record
            Publication::create($publicationData);
            DB::commit();

            Log::info('Publication created successfully.', ['title' => $request->title]);
            return redirect()->route('publication.index')->with('success', 'Publication created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create publication: ' . $e->getMessage());
             // Return validation errors if applicable, otherwise general error
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to save publication. Please check logs.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication): View
    {
         return view('admin.publication.show', compact('publication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publication $publication): View
    {
        return view('admin.publication.edit', compact('publication'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publication $publication): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:publications,title,' . $publication->id,
            'date' => 'required|date_format:Y-m-d',
            'pdf_file' => 'nullable|file|mimes:pdf|max:5120', // Nullable on update
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // Nullable on update
        ]);

        DB::beginTransaction();
        try {
            $publicationData = $validatedData;
            unset($publicationData['pdf_file'], $publicationData['image']); // Remove files initially

            // --- Handle PDF File Update (Directly to Public) ---
            if ($request->hasFile('pdf_file')) {
                // Delete old file if it exists
                if ($publication->pdf_file && File::exists(public_path($publication->pdf_file))) {
                    File::delete(public_path($publication->pdf_file));
                }
                // Store new file
                $file = $request->file('pdf_file');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/publications/pdfs');
                if (!File::isDirectory($destinationPath)) { File::makeDirectory($destinationPath, 0755, true, true); }
                $file->move($destinationPath, $fileName);
                $publicationData['pdf_file'] = 'uploads/publications/pdfs/' . $fileName; // Store relative path
            }

            // --- Handle Image Update using Trait ---
            // The trait handles deleting the old image if a new one is uploaded
            $imagePath = $this->handleImageUpdate($request, $publication, 'image', 'publications/images', 600, 400);
            $publicationData['image'] = $imagePath; // Trait returns old or new path

            // Update Publication record
            $publication->update($publicationData);
            DB::commit();

            Log::info('Publication updated successfully.', ['id' => $publication->id]);
            return redirect()->route('publication.index')->with('success', 'Publication updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update publication ID ' . $publication->id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to update publication. Please check logs.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     * --- UPDATED TO RETURN REDIRECTRESPONSE ---
     */
    public function destroy(Publication $publication): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Delete associated PDF file from public path
            if ($publication->pdf_file && File::exists(public_path($publication->pdf_file))) {
                File::delete(public_path($publication->pdf_file));
            }

            // Delete associated Image file (using path stored by trait)
            // Adjust path based on how your trait saves (relative to public/uploads or public/)
            $imageFullPath = public_path('uploads/' . $publication->image); // Assuming trait saves relative to public/uploads
             // Or: $imageFullPath = public_path($publication->image); // If trait saves relative to public/
             if ($publication->image && File::exists($imageFullPath)) {
                 File::delete($imageFullPath);
             }


            // Delete the publication record
            $publication->delete();
            DB::commit();

            // --- CHANGED ---
            return redirect()->route('publication.index')->with('success', 'Publication deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete publication ID {$publication->id}: " . $e->getMessage());
            
            // --- CHANGED ---
            return redirect()->route('publication.index')->with('error', 'Failed to delete publication.');
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGallery;
use App\Models\Client;
use App\Models\Country;
use App\Models\ProjectCategory;
use App\Traits\ImageUploadTrait; // Assuming you have this trait, though not used for gallery here
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image; // Import Intervention Image
// --- ADD THESE ---
use App\Imports\ProjectsImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Response;
// --- END ADD ---
class ProjectController extends Controller
{
    use ImageUploadTrait; // Trait is used for other images, keep it if needed elsewhere

    /**
     * Permissions middleware.
     */
    public function __construct()
    {
        // Adjust permission names as needed
        $this->middleware('permission:projectView', ['only' => ['index', 'data', 'show']]);
        $this->middleware('permission:projectAdd', ['only' => ['create', 'store']]);
        $this->middleware('permission:projectUpdate', ['only' => ['edit', 'update']]);
        $this->middleware('permission:projectDelete', ['only' => ['destroy', 'deleteGalleryImage']]);
    }

       protected function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        $query = Project::where('slug', $slug);
        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            // Reset query for the next loop iteration
            $query = Project::where('slug', $slug);
             if ($ignoreId !== null) {
                 $query->where('id', '!=', $ignoreId);
             }
        }
        return $slug;
    }
    // --- End Helper ---

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.project.index');
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            // Eager load relationships needed for the table display
            $query = Project::with(['client', 'country', 'category']);

            // Search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('client', fn($cq) => $cq->where('name', 'like', '%' . $searchTerm . '%'))
                      ->orWhereHas('country', fn($cq) => $cq->where('name', 'like', '%' . $searchTerm . '%'))
                      ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', '%' . $searchTerm . '%'))
                      ->orWhere('status', 'like', '%' . $searchTerm . '%');
                });
            }

            // Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            $allowedSorts = ['id', 'title', 'status', 'agreement_signing_date', 'created_at'];
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('id', 'desc'); // Fallback
            }

            $paginated = $query->paginate(10); // Adjust page size

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch projects: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve projects.'], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $clients = Client::orderBy('name')->get();
        $countries = Country::orderBy('name')->where('status', true)->get(); // Only active countries
        $categories = ProjectCategory::orderBy('name')->get();
        $statuses = ['pending', 'ongoing', 'complete']; // Define statuses
        return view('admin.project.create', compact('clients', 'countries', 'categories', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
       $validatedData = $request->validate([
            'title' => 'required|string|unique:projects,title',
            'description' => 'required|string',
            'service' => 'nullable|string',
            // --- MODIFIED VALIDATION ---
            'client_id' => 'required|exists:clients,id',
            'country_id' => 'required|exists:countries,id',
            'agreement_signing_date' => 'required|date_format:Y-m-d',
            // --- END MODIFICATION ---
            'is_flagship' => 'required|boolean',
            'category_id' => 'required|exists:project_categories,id',
            'status' => 'required|in:pending,ongoing,complete',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $projectData = $validatedData;
            unset($projectData['gallery_images']); // Remove gallery for project creation

            // --- Generate Slug ---
            $projectData['slug'] = $this->generateUniqueSlug($request->title);
            // --------------------
            // Create the Project
            $project = Project::create($projectData);

            // Handle Gallery Image Uploads
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $imageFile) {
                    $fileName = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
                    $dbPath = "public/uploads/project_gallery/{$project->id}/{$fileName}"; // Include project ID in path
                    $savePath = base_path($dbPath);
                    $directory = public_path("uploads/project_gallery/{$project->id}");

                    if (!File::isDirectory($directory)) {
                         File::makeDirectory($directory, 0755, true, true);
                     }

                    // Use Intervention Image to resize
                    $image = Image::read($imageFile);
                    $image->resize(600, 400); // Resize to exact dimensions 600x400
                    $image->save($savePath, 90); // Save with quality (adjust as needed)

                    // Create gallery record
                    $project->galleryImages()->create(['image_path' => $dbPath]);
                }
            }

            DB::commit();
            Log::info('Project created successfully.', ['id' => $project->id]);
            return redirect()->route('project.index')->with('success', 'Project created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create project: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to save project. Please check logs.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): View
    {
        // Eager load all necessary relationships
        $project->load(['client', 'country', 'category', 'galleryImages']);
        return view('admin.project.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        $project->load('galleryImages'); // Load gallery for editing
        $clients = Client::orderBy('name')->get();
        $countries = Country::orderBy('name')->where('status', true)->get();
        $categories = ProjectCategory::orderBy('name')->get();
        $statuses = ['pending', 'ongoing', 'complete'];
        return view('admin.project.edit', compact('project', 'clients', 'countries', 'categories', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
       $validatedData = $request->validate([
            'title' => 'required|string|unique:projects,title,' . $project->id,
            'description' => 'required|string',
             'service' => 'nullable|string',
             // --- MODIFIED VALIDATION ---
            'client_id' => 'required|exists:clients,id',
            'country_id' => 'required|exists:countries,id',
            'agreement_signing_date' => 'required|date_format:Y-m-d',
            // --- END MODIFICATION ---
            'is_flagship' => 'required|boolean',
            'category_id' => 'required|exists:project_categories,id',
            'status' => 'required|in:pending,ongoing,complete',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $projectData = $validatedData;
            unset($projectData['gallery_images']); // Remove gallery files from main update data

            // --- Regenerate Slug if Title Changed ---
             if ($request->title !== $project->title) {
                 $projectData['slug'] = $this->generateUniqueSlug($request->title, $project->id);
             }
            // ----------------------------------------
            // Update Project details
            $project->update($projectData);

            // Handle *New* Gallery Image Uploads
            if ($request->hasFile('gallery_images')) {
                // Same logic as in store method
                 foreach ($request->file('gallery_images') as $imageFile) {
                    $fileName = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
                    $dbPath = "public/uploads/project_gallery/{$project->id}/{$fileName}";
                    $savePath = base_path($dbPath);
                    $directory = public_path("uploads/project_gallery/{$project->id}");
                     if (!File::isDirectory($directory)) { File::makeDirectory($directory, 0755, true, true); }

                    // Use Intervention Image to resize
                    $image = Image::read($imageFile);
                    $image->resize(600, 400); // Resize to exact dimensions 600x400
                    $image->save($savePath, 90); // Save with quality

                    $project->galleryImages()->create(['image_path' => $dbPath]);
                }
            }
             // NOTE: Deleting existing gallery images is handled by the deleteGalleryImage method via AJAX

            DB::commit();
            Log::info('Project updated successfully.', ['id' => $project->id]);
            // Redirect back to edit page or index page
            return redirect()->route('project.edit', $project->id)->with('success', 'Project updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update project ID ' . $project->id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to update project. Please check logs.']);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Project $project): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Delete associated gallery image files first
            foreach ($project->galleryImages as $galleryImage) {
                // Use public_path() for files saved directly in public
                 if ($galleryImage->image_path && File::exists(public_path($galleryImage->image_path))) {
                     File::delete(public_path($galleryImage->image_path));
                 }
                // If using Storage::disk('public') use this instead:
                // if ($galleryImage->image_path && Storage::disk('public')->exists($galleryImage->image_path)) {
                //     Storage::disk('public')->delete($galleryImage->image_path);
                // }
            }
            // Delete the directory itself (optional)
            $directory = public_path("uploads/project_gallery/{$project->id}");
             if (File::isDirectory($directory)) {
                 File::deleteDirectory($directory); // Be cautious if other files might be there
             }
            // If using Storage::disk('public'):
            // Storage::disk('public')->deleteDirectory("uploads/project_gallery/{$project->id}");


            // Delete the project record (gallery images should cascade delete due to migration setup)
            $project->delete();

            DB::commit();
            // --- MODIFIED RESPONSE ---
            return redirect()->route('project.index')->with('success', 'Project and associated gallery deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete project ID {$project->id}: " . $e->getMessage());
            // --- MODIFIED RESPONSE ---
            return redirect()->route('project.index')->with('error', 'Failed to delete project.');
        }
    }

    /**
     * Delete a specific gallery image via AJAX.
     */
    public function deleteGalleryImage($imageId): JsonResponse
    {
        try {
            $galleryImage = ProjectGallery::findOrFail($imageId);

            // Optional: Authorization check
            // if (auth()->user()->cannot('update', $galleryImage->project)) { abort(403); }

            DB::beginTransaction();
            // Delete the file
             if ($galleryImage->image_path && File::exists(base_path($galleryImage->image_path))) {
                File::delete(base_path($galleryImage->image_path));
            }
            // Delete the record
            $galleryImage->delete();
            DB::commit();

            return response()->json(['message' => 'Gallery image deleted successfully.']);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             DB::rollBack(); // Ensure rollback on not found
             return response()->json(['error' => 'Image not found.'], 404);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete gallery image ID {$imageId}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete image.'], 500);
        }
    }

    /**
     * Show the form for importing projects.
     */
    public function createImport(): View
    {
        return view('admin.project.import');
    }

    /**
     * Handle the import of the Excel file.
     */
    public function storeImport(Request $request): RedirectResponse
    {

        // --- ADD THIS LINE ---
        set_time_limit(0); // 0 = unlimited execution time for THIS script run
        // --------------------
        
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ProjectsImport, $request->file('file'));
            
            return redirect()->route('project.index')->with('success', 'Projects imported successfully!');
        
        } catch (ValidationException $e) {
            // This catches validation errors from the ProjectsImport class
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            // Redirect back with errors
            return redirect()->back()->with('error', 'Import failed. Please check the following errors:')->with('import_errors', $errorMessages);
        
        } catch (Exception $e) {
            // Catches other errors (e.g., file system, database connection)
            Log::error('Project import failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred during import. Please check logs.');
        }
    }

    /**
     * Download the Excel template file.
     */
    public function downloadTemplate()
    {
        // IMPORTANT: You must create this file and place it in this path.
        $path = public_path('templates/projects_import_template.xlsx');

        if (!File::exists($path)) {
            // Handle file not found, e.g., redirect back with an error
            return redirect()->back()->with('error', 'Template file not found. Please contact admin.');
        }

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return Response::download($path, 'projects_import_template.xlsx', $headers);
    }

    
}
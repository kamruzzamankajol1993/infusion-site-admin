<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TrainingDocument;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Validation\Rule; // Import Rule for status validation
use Illuminate\Support\Str;
class TrainingController extends Controller
{
    use ImageUploadTrait; // Use the trait for the main image

    public function __construct()
    {
        // Permissions remain the same
        $this->middleware('permission:trainingView', ['only' => ['index', 'data', 'show']]);
        $this->middleware('permission:trainingAdd', ['only' => ['create', 'store']]);
        $this->middleware('permission:trainingUpdate', ['only' => ['edit', 'update']]);
        $this->middleware('permission:trainingDelete', ['only' => ['destroy']]);
    }


    private function filterEmptyDocumentRows(Request $request): void
{
    $documents = $request->input('documents', []);

    $filteredDocuments = array_filter($documents, function($doc, $index) use ($request) {
        $title = $doc['title'] ?? '';
        // Check if a file has been uploaded for this specific index.
        $filePresent = $request->hasFile("documents.{$index}.file");

        // Keep the row if title is non-empty OR a file is present.
        return !empty(trim($title)) || $filePresent;
    }, ARRAY_FILTER_USE_BOTH);

    $request->merge(['documents' => $filteredDocuments]);
}

    public function index(): View
    {
        return view('admin.training.index');
    }

    public function data(Request $request): JsonResponse
    {
        try {
            // $query = Training::with('category'); // No longer need to load category
            $query = Training::query();

            // Search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('training_venue', 'like', '%' . $searchTerm . '%');
                      // Removed ->orWhereHas('category', ...)
                });
            }

            // Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            // Added new sortable columns
            $allowedSorts = ['id', 'title', 'start_date', 'end_date', 'training_fee', 'created_at', 'status', 'deadline_for_registration'];
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('id', 'desc');
            }

            $paginated = $query->paginate(10);

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch trainings: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve trainings.'], 500);
        }
    }

    public function create(): View
    {
        // $categories = TrainingCategory::orderBy('name')->get(); // No longer needed
        // return view('admin.training.create', compact('categories'));
        return view('admin.training.create');
    }


    protected function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        $query = Training::where('slug', $slug);
        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            // Reset query for the next loop iteration
            $query = Training::where('slug', $slug);
             if ($ignoreId !== null) {
                 $query->where('id', '!=', $ignoreId);
             }
        }
        return $slug;
    }
    // --- End Helper ---

    /**
     * Handle PDF or other document uploads.
     *
     * @param Request $request
     * @param string $fieldName
     * @param string $directory
     * @param string|null $existingFilePath
     * @return string|null
     */
    private function handleDocumentUpload(Request $request, string $fieldName, string $directory, string $existingFilePath = null): ?string
    {
        if ($request->hasFile($fieldName)) {
            // Delete old file if it exists
            if ($existingFilePath && File::exists(base_path($existingFilePath))) {
                File::delete(base_path($existingFilePath));
            }

            $file = $request->file($fieldName);
            // Ensure unique name, preserve extension
            $fileName = uniqid($fieldName . '_') . '.' . $file->getClientOriginalExtension();
            $path = 'public/uploads/' . $directory;
            $file->move(base_path($path), $fileName);
            
            // Return the public-relative path for storage
            return $path . '/' . $fileName;
        }
        
        // Return the old path if no new file is uploaded
        return $existingFilePath;
    }


   public function store(Request $request): RedirectResponse
    {

        $this->filterEmptyDocumentRows($request);
        $validatedData = $request->validate([
            // 'category_id' => 'required|exists:training_categories,id', // Removed
            'title' => 'required|string|max:255|unique:trainings,title',
            'description' => 'nullable|string',
            'learn_from_training' => 'nullable|string',
            'who_should_attend' => 'nullable|string',
            'methodology' => 'nullable|string',
            'training_time' => 'nullable|string|max:255',
            'training_venue' => 'nullable|string|max:255',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'deadline_for_registration' => 'nullable|date_format:Y-m-d',
            'training_fee' => 'nullable|numeric|min:0',
            // 'requirement' => 'required|string', // Removed
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:1024',
            'status' => ['required', Rule::in(['upcoming', 'running', 'postponed', 'complete'])],
            // --- 2. ADD NEW DOCS VALIDATION ---
            'documents' => 'nullable|array',
            'documents.*.title' => 'required_with:documents.*.file|string|max:255', // Title required if file is present
            'documents.*.file' => 'required_with:documents.*.title|file|mimes:pdf|max:2048', // File required if title is present
            'skills' => 'nullable|array', // Kept
            // --- MODIFIED: Removed 'required' from skills.* ---
            'skills.*' => 'nullable|string|max:255', 
        ]);

        DB::beginTransaction();
        try {
            $trainingData = $validatedData;
            // Unset file fields before mass assignment
          unset($trainingData['skills'], $trainingData['image'], $trainingData['documents']); // <-- 3. Update unset
// --- Generate Slug ---
            $trainingData['slug'] = $this->generateUniqueSlug($request->title);
            // 1. Handle image upload
            $tempModel = new Training();
            // Assuming handleImageUpload saves relative to base_path('public/...')
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'trainings', 600, 400); 
            if ($imagePath) {
                // Adjust path if handleImageUpload doesn't return public/... format
                $trainingData['image'] = $imagePath; 
            } else {
                 throw new Exception("Training image upload failed or missing.");
            }

            // 2. Handle Document Uploads
            $docDir = 'trainings/documents';
           

            // 3. Create Training
            $training = Training::create($trainingData);

            // 4. Create Skills (Only if skills are provided and not empty)
            if ($request->filled('skills') && is_array($request->skills)) {
                foreach ($request->skills as $skillName) {
                    $trimmedSkill = trim($skillName);
                    if (!empty($trimmedSkill)) { // Ensure skill is not just whitespace
                        $training->skills()->create(['skill_name' => $trimmedSkill]);
                    }
                }
            }

            // --- 7. ADD NEW DOCUMENT UPLOAD LOGIC ---
            if ($request->has('documents')) {
                $docDir = 'trainings/documents';
                foreach ($request->documents as $doc) {
                    // Check if both title and file are present (validation should catch this, but good to double check)
                    if (isset($doc['title']) && isset($doc['file'])) {
                        
                        // --- THIS IS THE FIX ---
                        // Create a new request and put the file in the 'files' bag
                        $fileRequest = new Request(files: ['doc_file' => $doc['file']]);
                        // --- END FIX ---

                        $filePath = $this->handleDocumentUpload($fileRequest, 'doc_file', $docDir);

                        if ($filePath) {
                            $training->documents()->create([
                                'title' => $doc['title'],
                                'pdf_file' => $filePath,
                            ]);
                        }
                    }
                }
            }
            // --- END NEW DOC LOGIC ---

            DB::commit();
            Log::info('Training created successfully.', ['id' => $training->id]);
            return redirect()->route('training.index')->with('success', 'Training created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create training: ' . $e->getMessage());
            // Improved error reporting
            $errorMessage = 'Failed to save training.';
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return redirect()->back()->withInput()->withErrors($e->errors());
            } elseif (app()->environment('local')) { // Show detailed error in local env
                 $errorMessage .= ' Error: ' . $e->getMessage();
            } else {
                 $errorMessage .= ' Please check logs.';
            }
             return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    public function show(Training $training): View
    {
        $training->load('skills'); // Removed 'category'
        return view('admin.training.show', compact('training'));
    }

    public function edit(Training $training): View
    {
        $training->load('skills'); // Load skills for editing
        // $categories = TrainingCategory::orderBy('name')->get(); // No longer needed
        // return view('admin.training.edit', compact('training', 'categories'));
        return view('admin.training.edit', compact('training'));
    }

    public function update(Request $request, Training $training): RedirectResponse
    {

        $this->filterEmptyDocumentRows($request);
        $validatedData = $request->validate([
            // 'category_id' => 'required|exists:training_categories,id', // Removed
            'title' => 'required|string|max:255|unique:trainings,title,' . $training->id,
            'description' => 'nullable|string',
            'learn_from_training' => 'nullable|string',
            'who_should_attend' => 'nullable|string',
            'methodology' => 'nullable|string',
            'training_time' => 'nullable|string|max:255',
            'training_venue' => 'nullable|string|max:255',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'deadline_for_registration' => 'nullable|date_format:Y-m-d',
            'training_fee' => 'nullable|numeric|min:0',
            // 'requirement' => 'required|string', // Removed
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024',
            'status' => ['required', Rule::in(['upcoming', 'running', 'postponed', 'complete'])],
            // --- 10. ADD NEW DOCS VALIDATION ---
            'documents' => 'nullable|array',
            'documents.*.title' => 'required_with:documents.*.file|string|max:255',
            'documents.*.file' => 'nullable|file|mimes:pdf|max:2048', // File can be null if title exists (means old file)
             // We will handle logic for "existing" files
            'existing_documents' => 'nullable|array', // To track which old docs to keep
            'skills' => 'nullable|array', // Kept
            // --- MODIFIED: Removed 'required' from skills.* ---
            'skills.*' => 'nullable|string|max:255', 
        ]);

        DB::beginTransaction();
        try {
            $trainingData = $validatedData;
            // Unset file fields before mass assignment
           unset($trainingData['skills'], $trainingData['image'], $trainingData['documents'], $trainingData['existing_documents']);
// --- Regenerate Slug if Title Changed ---
             if ($request->title !== $training->title) {
                 $trainingData['slug'] = $this->generateUniqueSlug($request->title, $training->id);
             }
             // ----------------------------------------
            // 1. Handle image update
            $imagePath = $this->handleImageUpdate($request, $training, 'image', 'trainings', 600, 400);
            if($imagePath !== false) { // handleImageUpdate should return false on failure or no update
                $trainingData['image'] = $imagePath ?? $training->image; // Keep old if no new image
            } else {
                 // Handle potential upload failure if image was provided but failed
                 if ($request->hasFile('image')) {
                     throw new Exception("Image update failed.");
                 }
                 // If no new image was provided, keep the old one (already handled by default)
            }


            // 2. Handle Document Uploads (pass existing paths)
            $docDir = 'trainings/documents';
           
            // 3. Update Training details
            $training->update($trainingData);

            // 4. Re-create Skills (Delete old, add new if provided)
            $training->skills()->delete(); // Delete old skills first
            if ($request->filled('skills') && is_array($request->skills)) {
                foreach ($request->skills as $skillName) {
                     $trimmedSkill = trim($skillName);
                     if (!empty($trimmedSkill)) { // Only add non-empty skills
                        $training->skills()->create(['skill_name' => $trimmedSkill]);
                    }
                }
            }

            // --- 15. NEW DOCUMENT UPDATE LOGIC ---
            $docDir = 'trainings/documents';
            $existingDocIdsToKeep = $request->input('existing_documents', []);

            // First, delete documents that were *not* in the "existing_documents" array
            $docsToDelete = $training->documents()->whereNotIn('id', $existingDocIdsToKeep)->get();
            foreach ($docsToDelete as $doc) {
                if ($doc->pdf_file && File::exists(public_path($doc->pdf_file))) {
                    File::delete(public_path($doc->pdf_file));
                }
                $doc->delete();
            }

            // Second, add new documents
            if ($request->has('documents')) {
                foreach ($request->documents as $doc) {
                    // Only process new uploads (which *must* have a file)
                    if (isset($doc['title']) && isset($doc['file'])) {
                        
                        // --- THIS IS THE FIX ---
                        // Create a new request and put the file in the 'files' bag
                        $fileRequest = new Request(files: ['doc_file' => $doc['file']]);
                        // --- END FIX ---
                        
                        $filePath = $this->handleDocumentUpload($fileRequest, 'doc_file', $docDir);

                        if ($filePath) {
                            $training->documents()->create([
                                'title' => $doc['title'],
                                'pdf_file' => $filePath,
                            ]);
                        }
                    }
                }
            }
            // --- END NEW DOC LOGIC ---

            DB::commit();
            Log::info('Training updated successfully.', ['id' => $training->id]);
            return redirect()->route('training.index')->with('success', 'Training updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update training ID ' . $training->id . ': ' . $e->getMessage());
             // Improved error reporting
            $errorMessage = 'Failed to update training.';
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return redirect()->back()->withInput()->withErrors($e->errors());
            } elseif (app()->environment('local')) {
                 $errorMessage .= ' Error: ' . $e->getMessage();
            } else {
                 $errorMessage .= ' Please check logs.';
            }
             return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    // --- MODIFIED RETURN TYPE ---
    public function destroy(Training $training): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // --- Determine paths relative to public directory ---
            // 1. Delete image file (Using public_path based on other methods)
            if ($training->image) {
                $imageFullPath = public_path($training->image);
                if (File::exists($imageFullPath)) {
                    File::delete($imageFullPath);
                }
            }

            // 2. Delete document files (Using public_path)
            $documents = ['document_one', 'document_two', 'document_three', 'document_four'];
            foreach ($documents as $docField) {
                 if ($training->$docField) {
                     $docFullPath = public_path($training->$docField);
                     if (File::exists($docFullPath)) {
                         File::delete($docFullPath);
                     }
                 }
            }
            // --------------------------------------------------

            // 3. Delete training record (associated skills should cascade delete)
            $training->delete();

            DB::commit();
            // --- MODIFIED RESPONSE ---
            return redirect()->route('training.index')->with('success', 'Training deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete training ID {$training->id}: " . $e->getMessage());
            // --- MODIFIED RESPONSE ---
            return redirect()->route('training.index')->with('error', 'Failed to delete training.');
        }

    }
}
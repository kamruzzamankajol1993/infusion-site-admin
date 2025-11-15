<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career; // Import the model
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Str;
class CareerController extends Controller
{
    /**
     * Permissions middleware.
     */
    public function __construct()
    {
        // Adjust permission names as needed
        $this->middleware('permission:careerView', ['only' => ['index', 'data', 'show']]);
        $this->middleware('permission:careerAdd', ['only' => ['create', 'store']]);
        $this->middleware('permission:careerUpdate', ['only' => ['edit', 'update']]);
        $this->middleware('permission:careerDelete', ['only' => ['destroy']]);
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Build the query to check for existence
        $query = Career::where('slug', $slug);

        // If updating, ignore the current item's ID
        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        // Loop until a unique slug is found
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            // Reset query for the next loop
            $query = Career::where('slug', $slug);
            if ($ignoreId !== null) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
    // --- END HELPER FUNCTION ---

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.career.index');
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = Career::query();

            // Search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('company_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('position', 'like', '%' . $searchTerm . '%')
                      ->orWhere('job_location', 'like', '%' . $searchTerm . '%');
                });
            }

            // Sorting
            $sortColumn = $request->input('sort', 'application_deadline'); // Default sort
            $sortDirection = $request->input('direction', 'desc'); // Newest deadline first
            $allowedSorts = ['id', 'title', 'position', 'job_location', 'application_deadline', 'created_at'];
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('application_deadline', 'desc'); // Fallback sort
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
            Log::error('Failed to fetch careers: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve careers.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.career.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // All fields are required as per prompt
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:careers,title',
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'qualification' => 'required|string',
            'salary' => 'nullable|string|max:255', // <-- ADDED
            'age' => 'required|string|max:100', // Allow flexibility
            'experience' => 'required|string|max:255', // Allow flexibility
            'job_location' => 'required|string|max:255',
            'description' => 'required|string',
            'application_deadline' => 'required|date_format:Y-m-d',
            'email' => 'required|email|max:255',
        ]);

        try {

            // --- Add slug to validated data ---
            $validatedData['slug'] = $this->generateUniqueSlug($validatedData['title']);
            // --- End slug ---
            Career::create($validatedData);
            Log::info('Career created successfully.', ['title' => $request->title]);
            return redirect()->route('career.index')->with('success', 'Career posting created successfully.');
        } catch (Exception $e) {
            Log::error('Failed to create career: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to save career posting. Please check logs.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Career $career): View
    {
         return view('admin.career.show', compact('career'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Career $career): View
    {
        return view('admin.career.edit', compact('career'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Career $career): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:careers,title,' . $career->id,
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'qualification' => 'required|string',
            'age' => 'required|string|max:100',
            'salary' => 'nullable|string|max:255', // <-- ADDED
            'experience' => 'required|string|max:255',
            'job_location' => 'required|string|max:255',
            'description' => 'required|string',
            'application_deadline' => 'required|date_format:Y-m-d',
            'email' => 'required|email|max:255',
        ]);

        try {
            // --- Regenerate slug ONLY if title changed ---
         
                $validatedData['slug'] = $this->generateUniqueSlug($validatedData['title'], $career->id);
            
            // --- End slug ---
            $career->update($validatedData);
            Log::info('Career updated successfully.', ['id' => $career->id]);
            return redirect()->route('career.index')->with('success', 'Career posting updated successfully.');
        } catch (Exception $e) {
            Log::error('Failed to update career ID ' . $career->id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update career posting. Please check logs.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
   /**
     * Remove the specified resource from storage.
     * --- UPDATED TO RETURN REDIRECTRESPONSE ---
     */
    public function destroy(Career $career): RedirectResponse // <-- Changed to RedirectResponse
    {
        try {
            $careerTitle = $career->title; // Get title for log
            $career->delete();
            Log::info('Career deleted successfully.', ['title' => $careerTitle]);
            // Redirect back to index with success flash message
            return redirect()->route('career.index')->with('success', 'Career posting deleted successfully.');
        } catch (Exception $e) {
            Log::error("Failed to delete career ID {$career->id}: " . $e->getMessage());
            // Redirect back with error flash message
            return redirect()->route('career.index')->withErrors(['error' => 'Failed to delete career posting.']);
        }
    }
}
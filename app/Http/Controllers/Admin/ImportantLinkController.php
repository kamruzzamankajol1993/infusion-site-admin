<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportantLink; // Import the model
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse; // For store method redirect
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;

class ImportantLinkController extends Controller
{
    /**
     * Add permissions middleware (adjust as needed).
     */
    public function __construct()
    {
         $this->middleware('permission:importantLinkView|importantLinkAdd|importantLinkUpdate|importantLinkDelete', ['only' => ['index','data']]);
         $this->middleware('permission:importantLinkAdd', ['only' => ['store']]);
         $this->middleware('permission:importantLinkUpdate', ['only' => ['show', 'update']]); // show fetches data for edit
         $this->middleware('permission:importantLinkDelete', ['only' => ['destroy']]);
    }

    /**
     * Display the listing page.
     */
    public function index(): View
    {
        return view('admin.important_link.index');
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = ImportantLink::query();

            // Search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('link', 'like', '%' . $searchTerm . '%');
                });
            }

            // Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            $allowedSorts = ['id', 'title', 'link', 'created_at'];
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('id', 'desc'); // Fallback sort
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
            Log::error('Failed to fetch important links: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve links.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage (from Add modal).
     * Using RedirectResponse as the add modal likely uses a standard form post.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'required|url|max:255', // Validate as URL
        ]);

        try {
            ImportantLink::create($validated);
            Log::info('Important Link created successfully.', ['title' => $request->title]);
            return redirect()->route('importantLink.index')->with('success','Important Link created successfully!');
        } catch (Exception $e) {
            Log::error('Failed to create important link: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create link. Please check logs.');
        }
    }

    /**
     * Display the specified resource (used to fetch data for edit modal).
     */
    public function show($id): JsonResponse
    {
        try {
            $link = ImportantLink::findOrFail($id);
            return response()->json($link);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to fetch non-existent important link ID {$id}");
             return response()->json(['error' => 'Link not found.'], 404);
        } catch (Exception $e) {
            Log::error("Failed to fetch important link ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve link data.'], 500);
        }
    }

    /**
     * Update the specified resource in storage (from Edit modal AJAX).
     * Using POST with _method=PUT.
     */
    public function update(Request $request, $id): JsonResponse
    {
        // Find first to handle 404
        $link = ImportantLink::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'required|url|max:255', // Validate as URL
        ]);

        try {
            $link->update($validated);
            Log::info('Important Link updated successfully.', ['id' => $id]);
            return response()->json(['message' => 'Link updated successfully']);
        } catch (Exception $e) {
            Log::error("Failed to update important link ID {$id}: " . $e->getMessage());
             return response()->json(['error' => 'Failed to update link.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage (AJAX delete).
     * --- UPDATED TO RETURN REDIRECTRESPONSE ---
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $link = ImportantLink::findOrFail($id);
            $link->delete();
            
            // --- CHANGED ---
            return redirect()->route('importantLink.index')->with('success', 'Link deleted successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to delete non-existent important link ID {$id}");
             
             // --- CHANGED ---
             return redirect()->route('importantLink.index')->with('error', 'Link not found.');

        } catch (Exception $e) {
            Log::error("Failed to delete important link ID {$id}: " . $e->getMessage());
            // Check for foreign key constraint errors if this model gets linked elsewhere
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                 // --- CHANGED ---
                 return redirect()->route('importantLink.index')->with('error', 'Cannot delete this link as it might be associated with other records.');
            }
            // --- CHANGED ---
            return redirect()->route('importantLink.index')->with('error', 'Failed to delete link.');
        }
    }

     // --- Resource methods not used with modal approach ---
     public function create() { return abort(404); }
     public function edit($id) { return abort(404); }
     // ---------------------------------------------------
}
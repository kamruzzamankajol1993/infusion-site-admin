<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country; // Import the Country model
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse; // Needed for store method redirect
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Validator;
class CountryController extends Controller
{
    /**
     * Add permissions middleware (adjust permission names as needed).
     */
    function __construct()
    {
         // Assuming you have permissions like these
         $this->middleware('permission:countryView|countryAdd|countryUpdate|countryDelete', ['only' => ['index','data']]);
         $this->middleware('permission:countryAdd', ['only' => ['store']]);
         $this->middleware('permission:countryUpdate', ['only' => ['show', 'update']]); // show is used to fetch data for edit modal
         $this->middleware('permission:countryDelete', ['only' => ['destroy']]);
    }

    /**
     * Display the listing page.
     */
    public function index(): View
    {
        return view('admin.country.index'); // Points to the index blade file
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = Country::query();

            // Search functionality
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Sorting functionality
            $sortColumn = $request->input('sort', 'name'); // Default sort: name
            $sortDirection = $request->input('direction', 'asc');

            $allowedSortColumns = ['id', 'name', 'status', 'created_at']; // Valid columns
            if (in_array($sortColumn, $allowedSortColumns)) {
                 $query->orderBy($sortColumn, $sortDirection);
            } else {
                 $query->orderBy('name', 'asc'); // Fallback sort
            }

            // Pagination
            $paginated = $query->paginate(10); // Adjust page size (e.g., 10)

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch countries: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve countries.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage (from Add modal).
     * Using RedirectResponse as the add modal likely uses a standard form post.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name',
            'iso3' => 'required|string|size:2|unique:countries,iso3',
            // Status is optional here as it defaults to true in migration
            // 'status' => 'boolean'
        ]);

        try {
            Country::create([
                'name' => $request->name,
                'iso3' => $request->iso3,
                'status' => $request->input('status', true) // Default to true if not provided
            ]);
            Log::info('Country created successfully.', ['name' => $request->name]);
            return redirect()->route('country.index')->with('success','Country created successfully!');
        } catch (Exception $e) {
            Log::error('Failed to create country: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create country. Please check logs.');
        }
    }

    /**
     * Display the specified resource (used to fetch data for edit modal).
     */
    public function show($id): JsonResponse
    {
        try {
            $country = Country::findOrFail($id);
            return response()->json($country);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Attempted to fetch non-existent country ID {$id}");
             return response()->json(['error' => 'Country not found.'], 404);
        } catch (Exception $e) {
            Log::error("Failed to fetch country ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve country data.'], 500);
        }
    }

    /**
     * Update the specified resource in storage (from Edit modal).
     */
    public function update(Request $request, $id): RedirectResponse // <-- MODIFIED RETURN TYPE
    {
         // Use Validator::make to manually handle errors and error bags
         $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:countries,name,' . $id,
            'iso3' => 'required|string|size:2|unique:countries,iso3,' . $id,
            'status' => 'required|boolean', // Status is required on update
        ]);

        // If validation fails, redirect back with the 'update' error bag
        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator, 'update') // <-- Use named error bag 'update'
                         ->withInput()
                         ->with('error_modal_id', $id); // <-- Add this to re-open the modal
        }

        try {
            $country = Country::findOrFail($id);
            $country->update([
                 'name' => $request->name,
                 'iso3' => $request->iso3,
                 'status' => $request->status
            ]);
            Log::info('Country updated successfully.', ['id' => $id, 'new_name' => $request->name]);
            
            // --- MODIFIED RESPONSE ---
            return redirect()->route('country.index')->with('success', 'Country updated successfully');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to update non-existent country ID {$id}");
             // --- MODIFIED RESPONSE ---
             return redirect()->back()
                        ->withErrors(['error' => 'Country not found.'], 'update')
                        ->withInput()
                        ->with('error_modal_id', $id);
        } catch (Exception $e) {
            Log::error("Failed to update country ID {$id}: " . $e->getMessage());
             // --- MODIFIED RESPONSE ---
             return redirect()->back()
                        ->withErrors(['error' => 'Failed to update country.'], 'update')
                        ->withInput()
                        ->with('error_modal_id', $id);
        }
    }
    // --- END MODIFIED METHOD ---

    /**
     * Remove the specified resource from storage (single delete).
     */
    // --- MODIFIED RETURN TYPE ---
    public function destroy($id): RedirectResponse
    {
        try {
            $country = Country::findOrFail($id);

           

            $country->delete();

            // --- MODIFIED RESPONSE ---
            return redirect()->route('country.index')->with('success', 'Country deleted successfully.');

         } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to delete non-existent country ID {$id}");
             // --- MODIFIED RESPONSE ---
             return redirect()->route('country.index')->with('error', 'Country not found.');
        } catch (Exception $e) {
            Log::error("Failed to delete country ID {$id}: " . $e->getMessage());
             // --- MODIFIED RESPONSE ---
             // Consider a more specific error if it's due to foreign key constraints
             if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                  return redirect()->route('country.index')->with('error', 'Cannot delete country. It is likely associated with other records (e.g., projects).');
             }
             return redirect()->route('country.index')->with('error', 'Failed to delete country.');
        }
    }
}

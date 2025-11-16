<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Solution; // Import the model
use App\Traits\ImageUploadTrait; // Import the trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse; // For store method redirect
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // For deleting file
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SolutionController extends Controller
{
    use ImageUploadTrait; // Use the image upload trait

    /**
     * Add permissions middleware.
     */
    public function __construct()
    {
         // *** IMPORTANT: You must create these permissions in your seeder ***
         $this->middleware('permission:solutionView|solutionAdd|solutionUpdate|solutionDelete', ['only' => ['index','data']]);
         $this->middleware('permission:solutionAdd', ['only' => ['store']]);
         $this->middleware('permission:solutionUpdate', ['only' => ['show', 'update']]); // show fetches data for edit
         $this->middleware('permission:solutionDelete', ['only' => ['destroy']]);
    }

    /**
     * Display the listing page.
     */
    public function index(): View
    {
        // Point to the new view
        return view('admin.solution.index');
    }

   public function data(Request $request): JsonResponse
    {
        try {
            $query = Solution::query();

            // Search
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            $allowedSorts = ['id', 'name', 'created_at']; // Removed image_shape
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
            Log::error('Failed to fetch solutions: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve solutions.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage (from Add modal).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:solutions,name',
            // *** UPDATED: Max size changed ***
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:256', // Max 256KB
        ]);

        DB::beginTransaction();
        try {
            $solutionData = $request->only('name');

            // *** UPDATED: Dimensions changed ***
            $width = 60;
            $height = 60; // Set to 60 to enforce square

            // Handle image upload
            $tempModel = new Solution();
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'solutions', $width, $height); // Use trait
            if ($imagePath) {
                $solutionData['image'] = $imagePath;
            } else {
                 throw new Exception("Solution image upload failed or missing.");
            }

            Solution::create($solutionData);
            DB::commit();

            Log::info('Solution created successfully.', ['name' => $request->name]);
            return redirect()->route('solution.index')->with('success','Solution created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create solution: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to create solution. Please check logs.']);
        }
    }

    /**
     * Display the specified resource (used to fetch data for edit modal).
     */
    public function show($id): JsonResponse
    {
        try {
            $solution = Solution::findOrFail($id);
            // Add full image URL for preview in edit modal
            if ($solution->image) {
                $solution->image_url = asset($solution->image);
            } else {
                $solution->image_url = null;
            }
            return response()->json($solution);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to fetch non-existent solution ID {$id}");
             return response()->json(['error' => 'Solution not found.'], 404);
        } catch (Exception $e) {
            Log::error("Failed to fetch solution ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve solution data.'], 500);
        }
    }

    /**
     * Update the specified resource in storage (from Edit modal).
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:solutions,name,' . $id,
            // *** UPDATED: Max size changed ***
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:256', // Max 256KB
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator, 'update')
                         ->withInput()
                         ->with('error_modal_id', $id);
        }

        DB::beginTransaction();
        try {
            $solution = Solution::findOrFail($id);
            
            $solutionData = $request->only('name');

            // *** UPDATED: Dimensions changed ***
            $width = 60;
            $height = 60; // Set to 60 to enforce square

            if ($request->hasFile('image')) {
                $imagePath = $this->handleImageUpdate($request, $solution, 'image', 'solutions', $width, $height);
                $solutionData['image'] = $imagePath;
            }

            $solution->update($solutionData);
            DB::commit();

            Log::info('Solution updated successfully.', ['id' => $id]);
            return redirect()->route('solution.index')->with('success', 'Solution updated successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to update solution ID {$id}: " . $e->getMessage());
             return redirect()->back()
                        ->withErrors(['error' => 'Failed to update solution.'], 'update')
                        ->withInput()
                        ->with('error_modal_id', $id);
        }
    }


    /**
     * Remove the specified resource from storage (single delete).
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $solution = Solution::findOrFail($id);
            DB::beginTransaction();

            // Delete image file
            if ($solution->image && File::exists(public_path($solution->image))) {
                File::delete(public_path($solution->image));
            }

            $solution->delete();
            DB::commit();

            return redirect()->route('solution.index')->with('success', 'Solution deleted successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             DB::rollBack();
             Log::warning("Attempted to delete non-existent solution ID {$id}");
             return redirect()->route('solution.index')->with('error', 'Solution not found.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete solution ID {$id}: " . $e->getMessage());
            return redirect()->route('solution.index')->with('error', 'Failed to delete solution.');
        }
    }
}
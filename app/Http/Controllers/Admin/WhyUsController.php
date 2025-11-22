<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhyUs; // Import the model
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

class WhyUsController extends Controller
{
    use ImageUploadTrait; // Use the image upload trait

    /**
     * Add permissions middleware.
     */
    public function __construct()
    {
         // *** IMPORTANT: You must create these permissions: ***
         // whyUsView, whyUsAdd, whyUsUpdate, whyUsDelete
         $this->middleware('permission:whyUsView|whyUsAdd|whyUsUpdate|whyUsDelete', ['only' => ['index','data']]);
         $this->middleware('permission:whyUsAdd', ['only' => ['store']]);
         $this->middleware('permission:whyUsUpdate', ['only' => ['show', 'update']]); // show fetches data for edit
         $this->middleware('permission:whyUsDelete', ['only' => ['destroy']]);
    }

    /**
     * Display the listing page.
     */
    public function index(): View
    {
        // Point to the new view
        return view('admin.why_us.index');
    }

   public function data(Request $request): JsonResponse
    {
        try {
            $query = WhyUs::query();

            // Search
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            $allowedSorts = ['id', 'name', 'created_at'];
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
            Log::error('Failed to fetch Why Us data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage (from Add modal).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:why_us,name',
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:256', // Max 256KB
        ]);

        DB::beginTransaction();
        try {
            $whyUsData = $request->only('name', 'title');
            //$whyUsData['image'] = $imagePath;

            // Set dimensions to 60x60
            $width = 60;
            $height = 60; 

            // Handle image upload
            $tempModel = new WhyUs();
            // Store in a 'why_us' folder
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'why_us', $width, $height); 
            
            if ($imagePath) {
                $whyUsData['image'] = $imagePath;
            } else {
                 throw new Exception("'Why Us' image upload failed or missing.");
            }

            WhyUs::create($whyUsData);
            DB::commit();

            Log::info("'Why Us' item created successfully.", ['name' => $request->name]);
            return redirect()->route('why-us.index')->with('success','Item created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to create 'Why Us' item: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => "Failed to create item. Please check logs."]);
        }
    }

    /**
     * Display the specified resource (used to fetch data for edit modal).
     */
    public function show($id): JsonResponse
    {
        try {
            $whyUs = WhyUs::findOrFail($id);
            // Add full image URL for preview
            if ($whyUs->image) {
                $whyUs->image_url = asset($whyUs->image);
            } else {
                $whyUs->image_url = null;
            }
            return response()->json($whyUs);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to fetch non-existent Why Us ID {$id}");
             return response()->json(['error' => 'Item not found.'], 404);
        } catch (Exception $e) {
            Log::error("Failed to fetch Why Us ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve item data.'], 500);
        }
    }

    /**
     * Update the specified resource in storage (from Edit modal).
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:why_us,name,' . $id,
            'title' => 'nullable|string|max:255',
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
            $whyUs = WhyUs::findOrFail($id);
            
            $whyUsData = $request->only('name', 'title');

            // Set dimensions to 60x60
            $width = 60;
            $height = 60;

            if ($request->hasFile('image')) {
                // Store in 'why_us' folder
                $imagePath = $this->handleImageUpdate($request, $whyUs, 'image', 'why_us', $width, $height);
                $whyUsData['image'] = $imagePath;
            }

            $whyUs->update($whyUsData);
            DB::commit();

            Log::info("'Why Us' item updated successfully.", ['id' => $id]);
            return redirect()->route('why-us.index')->with('success', 'Item updated successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to update 'Why Us' item ID {$id}: " . $e->getMessage());
             return redirect()->back()
                        ->withErrors(['error' => 'Failed to update item.'], 'update')
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
            $whyUs = WhyUs::findOrFail($id);
            DB::beginTransaction();

            // Delete image file
            if ($whyUs->image && File::exists(public_path($whyUs->image))) {
                File::delete(public_path($whyUs->image));
            }

            $whyUs->delete();
            DB::commit();

            return redirect()->route('why-us.index')->with('success', 'Item deleted successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             DB::rollBack();
             Log::warning("Attempted to delete non-existent Why Us ID {$id}");
             return redirect()->route('why-us.index')->with('error', 'Item not found.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete Why Us ID {$id}: " . $e->getMessage());
            return redirect()->route('why-us.index')->with('error', 'Failed to delete item.');
        }
    }
}
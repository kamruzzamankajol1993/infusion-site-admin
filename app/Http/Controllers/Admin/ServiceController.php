<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceKeypoint; // Import keypoint model
use App\Traits\ImageUploadTrait; // Import trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // For deleting files
use Exception;

class ServiceController extends Controller
{
    use ImageUploadTrait; // Use the trait

    /**
     * Permissions middleware.
     */
    public function __construct()
    {
        // Adjust permission names as needed
        $this->middleware('permission:serviceView', ['only' => ['index', 'data', 'show']]);
        $this->middleware('permission:serviceAdd', ['only' => ['create', 'store']]);
        $this->middleware('permission:serviceUpdate', ['only' => ['edit', 'update']]);
        $this->middleware('permission:serviceDelete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // --- 1. For "Reorder" Tab ---
        $servicesForReorder = Service::orderBy('display_order', 'asc')
                                    ->get(['id', 'title']);

        // --- 2. For "Homepage Services" Tab ---
        
        // Get IDs of services already on the homepage, in their correct order
        $homepageServiceIds = Service::whereNotNull('homepage_display_order')
                                     ->orderBy('homepage_display_order', 'asc')
                                     ->pluck('id')
                                     ->toArray();
        
        // Fetch the service models for the homepage list
        $homepageServices = Service::whereIn('id', $homepageServiceIds)
                                   ->orderBy('homepage_display_order', 'asc')
                                   ->get(['id', 'title']);

        // Fetch all other services (that are NOT on the homepage) for the available list
        $availableServices = Service::whereNotIn('id', $homepageServiceIds)
                                    ->orderBy('title', 'asc')
                                    ->get(['id', 'title']);


        return view('admin.service.index', compact(
            'servicesForReorder', 
            'homepageServices', // <-- ADD THIS
            'availableServices' // <-- ADD THIS
        ));
    }
    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = Service::query();

            // Search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
            }

            // Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            $allowedSorts = ['id', 'title', 'created_at'];
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
            Log::error('Failed to fetch services: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve services.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.service.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:services,title',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // Max 1MB example
            'keypoints' => 'nullable|array', // Keypoints can be optional on create maybe? Adjust if required
            'keypoints.*' => 'required|string|max:500', // Each keypoint is required if the array exists
        ]);

        DB::beginTransaction();
        try {
            $serviceData = $validatedData;
            unset($serviceData['keypoints']); // Remove keypoints for service creation

            // Handle image upload using the trait
            $tempModel = new Service();
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'services', 740, 522); // Pass dimensions
            if ($imagePath) {
                $serviceData['image'] = $imagePath;
            } else {
                 throw new Exception("Service image upload failed or missing.");
            }

            // --- ADDED: Set display_order ---
            $maxOrder = Service::max('display_order') ?? 0;
            $serviceData['display_order'] = $maxOrder + 1;
            // --- END ADDED ---

            // Create the Service
            $service = Service::create($serviceData);

            // Create Keypoints
            if ($request->has('keypoints') && is_array($request->keypoints)) {
                foreach ($request->keypoints as $keypointText) {
                    if (!empty(trim($keypointText))) { // Ensure keypoint is not just whitespace
                        $service->keypoints()->create(['keypoint' => trim($keypointText)]);
                    }
                }
            }

            DB::commit();
            Log::info('Service created successfully.', ['id' => $service->id]);
            return redirect()->route('service.index')->with('success', 'Service created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create service: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to save service. Please check logs.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service): View
    {
        $service->load('keypoints'); // Eager load keypoints
        return view('admin.service.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service): View
    {
        $service->load('keypoints'); // Eager load keypoints
        return view('admin.service.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:services,title,' . $service->id,
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // Nullable on update
            'keypoints' => 'nullable|array',
            'keypoints.*' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $serviceData = $validatedData;
            unset($serviceData['keypoints']);

            // Handle image update using the trait
            $imagePath = $this->handleImageUpdate($request, $service, 'image', 'services', 740, 522); // Pass dimensions
            $serviceData['image'] = $imagePath; // Trait returns old path if no new image

            // Update the Service
            $service->update($serviceData);

            // Re-create Keypoints (delete old, add new)
            $service->keypoints()->delete(); // Delete existing keypoints
            if ($request->has('keypoints') && is_array($request->keypoints)) {
                foreach ($request->keypoints as $keypointText) {
                     if (!empty(trim($keypointText))) {
                        $service->keypoints()->create(['keypoint' => trim($keypointText)]);
                    }
                }
            }

            DB::commit();
            Log::info('Service updated successfully.', ['id' => $service->id]);
            return redirect()->route('service.index')->with('success', 'Service updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update service ID ' . $service->id . ': ' . $e->getMessage());
             return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to update service. Please check logs.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // --- MODIFIED RETURN TYPE ---
    public function destroy(Service $service): RedirectResponse
    {
        try {
            DB::beginTransaction();
            // Delete image file first (Using public_path based on trait)
            if ($service->image && File::exists(public_path($service->image))) {
                File::delete(public_path($service->image));
            }
            // If using Storage::disk('public'), adjust accordingly

            // Delete the service (keypoints should cascade delete due to migration/model setup)
            $service->delete();

            DB::commit();
            // --- MODIFIED RESPONSE ---
            return redirect()->route('service.index')->with('success', 'Service deleted successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete service ID {$service->id}: " . $e->getMessage());
            // --- MODIFIED RESPONSE ---
             // Check if it's a foreign key constraint error
             if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                 return redirect()->route('service.index')->with('error', 'Cannot delete this service as it might be linked to other data.');
             }
             return redirect()->route('service.index')->with('error', 'Failed to delete service.');
        }
    }

    /**
     * --- ADD THIS NEW METHOD ---
     * Update the display order of services.
     */
    /**
     * Update the display order of services.
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:services,id',
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->order as $index => $id) {
                // Update each service with its new order (index + 1)
                Service::where('id', $id)->update(['display_order' => $index + 1]);
            }
            DB::commit();
            return response()->json(['message' => 'Service order updated successfully!']);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update service order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update order.'], 500);
        }
    }

    /**
     * --- ADD THIS NEW METHOD ---
     * Update the homepage display order of services.
     */
    public function updateHomepageOrder(Request $request): JsonResponse
    {
        // Validate that we receive an array, and it doesn't have more than 6 items
        $request->validate([
            'order' => 'required|array|max:6', // Max 6 items
            'order.*' => 'integer|exists:services,id',
        ]);

        try {
            $orderedIds = $request->order;

            DB::beginTransaction();
            
            // 1. Reset all homepage orders to NULL
            Service::query()->update(['homepage_display_order' => null]);

            // 2. Set the new order for the selected services
            foreach ($orderedIds as $index => $id) {
                Service::where('id', $id)->update(['homepage_display_order' => $index + 1]);
            }

            DB::commit();
            return response()->json(['message' => 'Homepage service order updated successfully!']);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update homepage service order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update order.'], 500);
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client; // Import the model
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

class ClientController extends Controller
{
    use ImageUploadTrait; // Use the image upload trait

    /**
     * Add permissions middleware.
     */
    public function __construct()
    {
         // Adjust permission names as needed
         $this->middleware('permission:clientView|clientAdd|clientUpdate|clientDelete', ['only' => ['index','data']]);
         $this->middleware('permission:clientAdd', ['only' => ['store']]);
         $this->middleware('permission:clientUpdate', ['only' => ['show', 'update']]); // show fetches data for edit
         $this->middleware('permission:clientDelete', ['only' => ['destroy']]);
    }

    /**
     * Display the listing page.
     */
    public function index(): View
    {
        return view('admin.client.index');
    }

   public function data(Request $request): JsonResponse
    {
        try {
            $query = Client::query();

            // Search
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            // --- Updated allowed sorts ---
            $allowedSorts = ['id', 'name', 'created_at', 'image_shape'];
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
            Log::error('Failed to fetch clients: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve clients.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage (from Add modal).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:clients,name',
            'image_shape' => 'required|string|in:square,rectangular', // <-- Add validation
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:512', // Max 512KB example
        ]);

        DB::beginTransaction();
        try {
            // --- Updated to include image_shape ---
            $clientData = $request->only('name', 'image_shape');

            // --- Determine dimensions based on shape ---
            $shape = $request->input('image_shape');
            $width = ($shape === 'square') ? 84 : 200;
            $height = ($shape === 'square') ? 80 : 80;

            // Handle logo upload
            $tempModel = new Client();
            // --- Use dynamic dimensions ---
            $logoPath = $this->handleImageUpload($request, $tempModel, 'logo', 'clients', $width, $height); // Use trait
            if ($logoPath) {
                $clientData['logo'] = $logoPath;
            } else {
                 throw new Exception("Client logo upload failed or missing.");
            }

            Client::create($clientData);
            DB::commit();

            Log::info('Client created successfully.', ['name' => $request->name]);
            return redirect()->route('client.index')->with('success','Client created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create client: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to create client. Please check logs.']);
        }
    }

    /**
     * Display the specified resource (used to fetch data for edit modal).
     */
    public function show($id): JsonResponse
    {
        try {
            $client = Client::findOrFail($id);
            // Add full logo URL for preview in edit modal
            if ($client->logo) {
                $client->logo_url = asset($client->logo);
            } else {
                $client->logo_url = null;
            }
            // --- No change needed, image_shape is included automatically ---
            return response()->json($client); 
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to fetch non-existent client ID {$id}");
             return response()->json(['error' => 'Client not found.'], 404);
        } catch (Exception $e) {
            Log::error("Failed to fetch client ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve client data.'], 500);
        }
    }

    /**
     * Update the specified resource in storage (from Edit modal).
     * Note: Using POST with _method=PUT from AJAX.
     */
    // --- MODIFIED METHOD ---
    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:clients,name,' . $id,
            'image_shape' => 'required|string|in:square,rectangular', 
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:512',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator, 'update') 
                         ->withInput()
                         ->with('error_modal_id', $id); 
        }

        DB::beginTransaction();
        try {
            $client = Client::findOrFail($id); 
            
            $clientData = $request->only('name', 'image_shape');

            $shape = $request->input('image_shape');
            $width = ($shape === 'square') ? 84 : 200;
            $height = ($shape === 'square') ? 80 : 80;

            // --- !! MODIFIED LOGIC !! ---
            // Only call the trait if a new file is *actually* uploaded.
            // If no new file is sent, the 'logo' key is not added to $clientData,
            // and the database field remains unchanged.
            if ($request->hasFile('logo')) {
                // This will now only be called when a file exists, guaranteeing a string return
                $logoPath = $this->handleImageUpdate($request, $client, 'logo', 'clients', $width, $height); 
                $clientData['logo'] = $logoPath;
            }
            // --- !! END MODIFICATION !! ---

            $client->update($clientData);
            DB::commit();

            Log::info('Client updated successfully.', ['id' => $id]);
            return redirect()->route('client.index')->with('success', 'Client updated successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to update client ID {$id}: " . $e->getMessage());
             return redirect()->back()
                        ->withErrors(['error' => 'Failed to update client.'], 'update') 
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
            $client = Client::findOrFail($id);
            DB::beginTransaction();

            // Delete logo file first (Using public_path based on handleImageUpload)
            if ($client->logo && File::exists(public_path($client->logo))) {
                File::delete(public_path($client->logo));
            }
            // If using Storage::disk('public'), use:
            // if ($client->logo && Storage::disk('public')->exists($client->logo)) {
            //     Storage::disk('public')->delete($client->logo);
            // }

            $client->delete();
            DB::commit();

            // --- MODIFIED RESPONSE ---
            return redirect()->route('client.index')->with('success', 'Client deleted successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             DB::rollBack(); // Ensure rollback on not found
             Log::warning("Attempted to delete non-existent client ID {$id}");
             // --- MODIFIED RESPONSE ---
             return redirect()->route('client.index')->with('error', 'Client not found.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete client ID {$id}: " . $e->getMessage());
            // --- MODIFIED RESPONSE ---
            return redirect()->route('client.index')->with('error', 'Failed to delete client.');
        }
    }
}
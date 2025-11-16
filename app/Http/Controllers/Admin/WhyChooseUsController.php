<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhyChooseUs; // Import the model
use App\Traits\ImageUploadTrait; // Import the trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WhyChooseUsController extends Controller
{
    use ImageUploadTrait; // Use the image upload trait

    /**
     * Add permissions middleware.
     */
    public function __construct()
    {
         // *** IMPORTANT: Create these permissions ***
         $this->middleware('permission:whyChooseUsView|whyChooseUsAdd|whyChooseUsUpdate|whyChooseUsDelete', ['only' => ['index','data']]);
         $this->middleware('permission:whyChooseUsAdd', ['only' => ['store']]);
         $this->middleware('permission:whyChooseUsUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:whyChooseUsDelete', ['only' => ['destroy']]);
    }

    /**
     * Display the listing page with tabs.
     */
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'table'); // Default to 'table' tab
        $items = [];

        if ($activeTab === 'reorder') {
            $items = WhyChooseUs::orderBy('order', 'asc')->get();
        }

        return view('admin.why_choose_us.index', compact('activeTab', 'items'));
    }

   /**
    * Provide data for the AJAX Data Table.
    */
   public function data(Request $request): JsonResponse
    {
        try {
            $query = WhyChooseUs::query();

            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            $sortColumn = $request->input('sort', 'order');
            $sortDirection = $request->input('direction', 'asc');
            $allowedSorts = ['id', 'title', 'order', 'created_at'];
            
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('order', 'asc');
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
            Log::error('Failed to fetch Why Choose Us data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // Max 1MB
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only('title', 'description');

            // Set dimensions to 300x400
            $width = 300;
            $height = 400; 

            $tempModel = new WhyChooseUs();
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'why_choose_us', $width, $height); 
            
            if ($imagePath) {
                $data['image'] = $imagePath;
            } else {
                 throw new Exception("Image upload failed or missing.");
            }

            // 'order' is set automatically by the model
            WhyChooseUs::create($data);
            DB::commit();

            Log::info("Why Choose Us item created successfully.", ['title' => $request->title]);
            return redirect()->route('why-choose-us.index')->with('success','Item created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to create Why Choose Us item: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => "Failed to create item. Please check logs."]);
        }
    }

    /**
     * Display the specified resource (for edit modal).
     */
    public function show($id): JsonResponse
    {
        try {
            $item = WhyChooseUs::findOrFail($id);
            if ($item->image) {
                $item->image_url = asset($item->image);
            } else {
                $item->image_url = null;
            }
            // Description is returned automatically
            return response()->json($item);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to fetch non-existent Why Choose Us ID {$id}");
             return response()->json(['error' => 'Item not found.'], 404);
        } catch (Exception $e) {
            Log::error("Failed to fetch Why Choose Us ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve item data.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // Max 1MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator, 'update')
                         ->withInput()
                         ->with('error_modal_id', $id);
        }

        DB::beginTransaction();
        try {
            $item = WhyChooseUs::findOrFail($id);
            $data = $request->only('title', 'description');

            $width = 300;
            $height = 400;

            if ($request->hasFile('image')) {
                $imagePath = $this->handleImageUpdate($request, $item, 'image', 'why_choose_us', $width, $height);
                $data['image'] = $imagePath;
            }

            $item->update($data);
            DB::commit();

            Log::info("Why Choose Us item updated successfully.", ['id' => $id]);
            return redirect()->route('why-choose-us.index')->with('success', 'Item updated successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to update Why Choose Us item ID {$id}: " . $e->getMessage());
             return redirect()->back()
                        ->withErrors(['error' => 'Failed to update item.'], 'update')
                        ->withInput()
                        ->with('error_modal_id', $id);
        }
    }

    /**
     * Handle the AJAX request to update the order.
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate(['itemIds' => 'required|array']);

        DB::beginTransaction();
        try {
            foreach ($request->itemIds as $index => $id) {
                WhyChooseUs::where('id', $id)->update(['order' => $index + 1]);
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update order: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $item = WhyChooseUs::findOrFail($id);
            DB::beginTransaction();

            if ($item->image && File::exists(public_path($item->image))) {
                File::delete(public_path($item->image));
            }

            $item->delete();
            DB::commit();
            
            // Re-order remaining items
            DB::statement('SET @count = 0;');
            DB::update('UPDATE why_choose_us SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');

            return redirect()->route('why-choose-us.index')->with('success', 'Item deleted successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             DB::rollBack();
             Log::warning("Attempted to delete non-existent Why Choose Us ID {$id}");
             return redirect()->route('why-choose-us.index')->with('error', 'Item not found.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete Why Choose Us ID {$id}: " . $e->getMessage());
            return redirect()->route('why-choose-us.index')->with('error', 'Failed to delete item.');
        }
    }
}
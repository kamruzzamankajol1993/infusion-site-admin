<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebSolutionInclude;
use App\Traits\ImageUploadTrait; // Import Trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class WebSolutionIncludeController extends Controller
{
    use ImageUploadTrait; // Use Trait

    public function __construct() {
         $this->middleware('permission:webSolutionIncludeView|webSolutionIncludeAdd|webSolutionIncludeUpdate|webSolutionIncludeDelete', ['only' => ['index','data']]);
         $this->middleware('permission:webSolutionIncludeAdd', ['only' => ['store']]);
         $this->middleware('permission:webSolutionIncludeUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:webSolutionIncludeDelete', ['only' => ['destroy']]);
    }

    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? WebSolutionInclude::orderBy('order', 'asc')->get() : [];
        return view('admin.web_solution_include.index', compact('activeTab', 'items'));
    }

    public function data(Request $request): JsonResponse {
        try {
            $query = WebSolutionInclude::query();
            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }
            $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
            $paginated = $query->paginate(10);
            return response()->json($paginated);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    public function store(Request $request): RedirectResponse {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            // Updated validation for image
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:256', // 80x80 approx max size
        ]);

        try {
            $data = $request->only('title', 'description');
            
            // Handle Image Upload (80x80)
            $tempModel = new WebSolutionInclude();
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'web_solution_includes', 80, 80);
            
            if ($imagePath) {
                $data['image'] = $imagePath;
            } else {
                throw new Exception("Image upload failed.");
            }

            WebSolutionInclude::create($data);
            return redirect()->route('webSolution.include.index')->with('success','Item created successfully!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id): JsonResponse {
        try {
            $item = WebSolutionInclude::findOrFail($id);
            // Append full image URL for preview
            if ($item->image) {
                $item->image_url = asset($item->image);
            }
            return response()->json($item);
        } catch (Exception $e) {
             return response()->json(['error' => 'Item not found.'], 404);
        }
    }

    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:256',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }

        try {
            $item = WebSolutionInclude::findOrFail($id);
            $data = $request->only('title', 'description');

            // Handle Image Update
            if ($request->hasFile('image')) {
                $imagePath = $this->handleImageUpdate($request, $item, 'image', 'web_solution_includes', 80, 80);
                $data['image'] = $imagePath;
            }

            $item->update($data);
            return redirect()->route('webSolution.include.index')->with('success', 'Item updated successfully');
        } catch (Exception $e) {
             return redirect()->back()->withErrors(['error' => 'Failed to update item.'], 'update')->withInput()->with('error_modal_id', $id);
        }
    }

    public function updateOrder(Request $request): JsonResponse {
        $request->validate(['itemIds' => 'required|array']);
        try {
            foreach ($request->itemIds as $index => $id) {
                WebSolutionInclude::where('id', $id)->update(['order' => $index + 1]);
            }
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500);
        }
    }

    public function destroy($id): RedirectResponse {
        try {
            $item = WebSolutionInclude::findOrFail($id);
            
            // Delete image file
            if ($item->image && File::exists(public_path($item->image))) {
                File::delete(public_path($item->image));
            }

            $item->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE web_solution_includes SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('webSolution.include.index')->with('success', 'Item deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('webSolution.include.index')->with('error', 'Failed to delete item.');
        }
    }
}
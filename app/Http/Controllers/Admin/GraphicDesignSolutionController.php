<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GraphicDesignSolution;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class GraphicDesignSolutionController extends Controller
{
    use ImageUploadTrait;

    public function __construct()
    {
         $this->middleware('permission:graphicDesignSolutionView|graphicDesignSolutionAdd|graphicDesignSolutionUpdate|graphicDesignSolutionDelete', ['only' => ['index','data']]);
         $this->middleware('permission:graphicDesignSolutionAdd', ['only' => ['store']]);
         $this->middleware('permission:graphicDesignSolutionUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:graphicDesignSolutionDelete', ['only' => ['destroy']]);
    }

    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'table');
        $items = [];
        if ($activeTab === 'reorder') {
            $items = GraphicDesignSolution::orderBy('order', 'asc')->get();
        }
        return view('admin.graphic_design_solution.index', compact('activeTab', 'items'));
    }

   public function data(Request $request): JsonResponse
    {
        try {
            $query = GraphicDesignSolution::query();
            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }
            $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
            $paginated = $query->paginate(10);
            return response()->json($paginated);
        } catch (Exception $e) {
            Log::error('Failed to fetch solutions: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:256', // 80x80
        ]);

        try {
            $data = $request->only('title', 'description');
            $tempModel = new GraphicDesignSolution();
            $imagePath = $this->handleImageUpload($request, $tempModel, 'icon', 'graphic_design_solutions', 80, 80); 
            if (!$imagePath) throw new Exception("Icon upload failed.");
            $data['icon'] = $imagePath;
            
            GraphicDesignSolution::create($data);
            return redirect()->route('graphicDesign.solution.index')->with('success','Solution created successfully!');
        } catch (Exception $e) {
            Log::error("Failed to create solution: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => "Failed to create solution."]);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $item = GraphicDesignSolution::findOrFail($id);
            if ($item->icon) $item->icon_url = asset($item->icon);
            return response()->json($item);
        } catch (Exception $e) {
             return response()->json(['error' => 'Item not found.'], 404);
        }
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:256', // 80x80
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        
        try {
            $item = GraphicDesignSolution::findOrFail($id);
            $data = $request->only('title', 'description');
            if ($request->hasFile('icon')) {
                $data['icon'] = $this->handleImageUpdate($request, $item, 'icon', 'graphic_design_solutions', 80, 80);
            }
            $item->update($data);
            return redirect()->route('graphicDesign.solution.index')->with('success', 'Solution updated successfully');
        } catch (Exception $e) {
            Log::error("Failed to update solution ID {$id}: " . $e->getMessage());
             return redirect()->back()->withErrors(['error' => 'Failed to update solution.'], 'update')->withInput()->with('error_modal_id', $id);
        }
    }

    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate(['itemIds' => 'required|array']);
        try {
            foreach ($request->itemIds as $index => $id) {
                GraphicDesignSolution::where('id', $id)->update(['order' => $index + 1]);
            }
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
        } catch (Exception $e) {
            Log::error('Failed to update solution order: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500);
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $item = GraphicDesignSolution::findOrFail($id);
            if ($item->icon && File::exists(public_path($item->icon))) {
                File::delete(public_path($item->icon));
            }
            $item->delete();
            DB::statement('SET @count = 0;');
            DB::update('UPDATE graphic_design_solutions SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
            return redirect()->route('graphicDesign.solution.index')->with('success', 'Solution deleted successfully.');
        } catch (Exception $e) {
            Log::error("Failed to delete solution ID {$id}: " . $e->getMessage());
            return redirect()->route('graphicDesign.solution.index')->with('error', 'Failed to delete solution.');
        }
    }
}
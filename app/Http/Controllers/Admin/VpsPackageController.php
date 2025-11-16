<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VpsPackage;
use App\Models\VpsPackageCategory; // Needed for dropdown
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VpsPackageController extends Controller
{
    public function __construct() {
         $this->middleware('permission:vpsPackageView|vpsPackageAdd|vpsPackageUpdate|vpsPackageDelete', ['only' => ['index','data']]);
         $this->middleware('permission:vpsPackageAdd', ['only' => ['store']]);
         $this->middleware('permission:vpsPackageUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:vpsPackageDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? VpsPackage::with('category')->orderBy('order', 'asc')->get() : [];
        $categories = VpsPackageCategory::orderBy('name', 'asc')->pluck('name', 'id');
        return view('admin.vps_package.index', compact('activeTab', 'items', 'categories'));
    }
    public function data(Request $request): JsonResponse {
        $query = VpsPackage::with('category');
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('category', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }
        $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
        return response()->json($query->paginate(10));
    }
    public function store(Request $request): RedirectResponse {
        $data = $this->validatePackage($request);
        VpsPackage::create($data);
        return redirect()->route('vpsPage.package.index')->with('success','Package created successfully!');
    }
    public function show($id): JsonResponse {
        return response()->json(VpsPackage::findOrFail($id));
    }
    public function update(Request $request, $id): RedirectResponse {
        $data = $this->validatePackage($request, $id);
        VpsPackage::findOrFail($id)->update($data);
        return redirect()->route('vpsPage.package.index')->with('success', 'Package updated successfully');
    }
    public function updateOrder(Request $request): JsonResponse { 
        $request->validate(['itemIds' => 'required|array']);
        foreach ($request->itemIds as $index => $id) {
            VpsPackage::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
    }
    public function destroy($id): RedirectResponse {
        VpsPackage::findOrFail($id)->delete();
        DB::statement('SET @count = 0;');
        DB::update('UPDATE vps_packages SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
        return redirect()->route('vpsPage.package.index')->with('success', 'Package deleted successfully.');
    }
    
    // Helper function to validate and process features
    private function validatePackage(Request $request, $id = null): array {
        $validated = $request->validate([
            'category_id' => 'required|exists:vps_package_categories,id',
            'title' => 'required|string|max:255',
            'price_subtitle' => 'required|string|max:100',
            'price' => 'required|string|max:100',
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
            'is_stocked_out' => 'nullable|string', // 'on' or null
            'features_icon' => 'required|array',
            'features_icon.*' => 'required|string',
            'features_text' => 'required|array',
            'features_text.*' => 'required|string',
        ]);

        $features = [];
        foreach ($validated['features_text'] as $index => $text) {
            if (!empty($text)) { // Ensure text is not empty
                $features[] = [
                    'icon' => $validated['features_icon'][$index] ?? 'mdi:check',
                    'text' => $text,
                ];
            }
        }
        $validated['features'] = $features; // Add processed features to data
        $validated['is_stocked_out'] = isset($validated['is_stocked_out']) && $validated['is_stocked_out'] === 'on';
        
        // Remove old keys
        unset($validated['features_icon'], $validated['features_text']);

        return $validated;
    }
}
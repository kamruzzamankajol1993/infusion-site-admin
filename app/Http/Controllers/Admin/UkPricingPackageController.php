<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UkPricingPackage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UkPricingPackageController extends Controller
{
    public function __construct() {
         $this->middleware('permission:ukPricingPackageView|ukPricingPackageAdd|ukPricingPackageUpdate|ukPricingPackageDelete', ['only' => ['index','data']]);
         $this->middleware('permission:ukPricingPackageAdd', ['only' => ['store']]);
         $this->middleware('permission:ukPricingPackageUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:ukPricingPackageDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? UkPricingPackage::orderBy('order', 'asc')->get() : [];
        return view('admin.uk_pricing_package.index', compact('activeTab', 'items'));
    }
    public function data(Request $request): JsonResponse {
        $query = UkPricingPackage::query();
        if ($request->filled('search')) { $query->where('title', 'like', '%' . $request->search . '%'); }
        $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
        return response()->json($query->paginate(10));
    }
    public function store(Request $request): RedirectResponse {
        $data = $this->validatePackage($request);
        UkPricingPackage::create($data);
        return redirect()->route('ukCompany.package.index')->with('success','Package created successfully!');
    }
    public function show($id): JsonResponse {
        return response()->json(UkPricingPackage::findOrFail($id));
    }
    public function update(Request $request, $id): RedirectResponse {
        $data = $this->validatePackage($request, $id);
        UkPricingPackage::findOrFail($id)->update($data);
        return redirect()->route('ukCompany.package.index')->with('success', 'Package updated successfully');
    }
    public function updateOrder(Request $request): JsonResponse { 
        $request->validate(['itemIds' => 'required|array']);
        foreach ($request->itemIds as $index => $id) {
            UkPricingPackage::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
    }
    public function destroy($id): RedirectResponse {
        UkPricingPackage::findOrFail($id)->delete();
        DB::statement('SET @count = 0;');
        DB::update('UPDATE uk_pricing_packages SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
        return redirect()->route('ukCompany.package.index')->with('success', 'Package deleted successfully.');
    }
    
    // Helper function to validate and process features
    private function validatePackage(Request $request, $id = null): array {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|string|max:100',
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
            'features_text' => 'required|array',
            'features_text.*' => 'required|string',
            'features_included' => 'nullable|array',
            'features_included.*' => 'nullable|string', // 'on' or null
        ]);

        $features = [];
        foreach ($validated['features_text'] as $index => $text) {
            $features[] = [
                'text' => $text,
                'included' => isset($validated['features_included'][$index]) && $validated['features_included'][$index] === 'on'
            ];
        }
        $validated['features'] = $features; // Add processed features to data
        
        // Remove old keys
        unset($validated['features_text'], $validated['features_included']);

        return $validated;
    }
}
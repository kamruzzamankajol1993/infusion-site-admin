<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VpsPackageCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VpsPackageCategoryController extends Controller
{
    public function __construct() {
         $this->middleware('permission:vpsCategoryView|vpsCategoryAdd|vpsCategoryUpdate|vpsCategoryDelete', ['only' => ['index','data']]);
         $this->middleware('permission:vpsCategoryAdd', ['only' => ['store']]);
         $this->middleware('permission:vpsCategoryUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:vpsCategoryDelete', ['only' => ['destroy']]);
    }
    public function index(Request $request): View {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? VpsPackageCategory::orderBy('order', 'asc')->get() : [];
        return view('admin.vps_package_category.index', compact('activeTab', 'items'));
    }
    public function data(Request $request): JsonResponse {
        $query = VpsPackageCategory::query();
        if ($request->filled('search')) { $query->where('name', 'like', '%' . $request->search . '%'); }
        $query->orderBy($request->input('sort', 'order'), $request->input('direction', 'asc'));
        return response()->json($query->paginate(10));
    }
    public function store(Request $request): RedirectResponse {
        $request->validate(['name' => 'required|string|max:255|unique:vps_package_categories,name']);
        VpsPackageCategory::create(['name' => $request->name]);
        return redirect()->route('vpsPage.category.index')->with('success','Category created successfully!');
    }
    public function show($id): JsonResponse {
        return response()->json(VpsPackageCategory::findOrFail($id));
    }
    public function update(Request $request, $id): RedirectResponse {
        $validator = Validator::make($request->all(), ['name' => 'required|string|max:255|unique:vps_package_categories,name,' . $id]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }
        VpsPackageCategory::findOrFail($id)->update(['name' => $request->name]);
        return redirect()->route('vpsPage.category.index')->with('success', 'Category updated successfully');
    }
    public function updateOrder(Request $request): JsonResponse { 
        $request->validate(['itemIds' => 'required|array']);
        foreach ($request->itemIds as $index => $id) {
            VpsPackageCategory::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
    }
    public function destroy($id): RedirectResponse {
        // Deleting a category will also delete all packages in it (due to onDelete('cascade'))
        VpsPackageCategory::findOrFail($id)->delete();
        DB::statement('SET @count = 0;');
        DB::update('UPDATE vps_package_categories SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');
        return redirect()->route('vpsPage.category.index')->with('success', 'Category deleted successfully.');
    }
}
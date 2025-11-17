<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPackage;
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
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    use ImageUploadTrait;

    public function __construct()
    {
         $this->middleware('permission:productView|productAdd|productUpdate|productDelete', ['only' => ['index','data']]);
         $this->middleware('permission:productAdd', ['only' => ['store']]);
         $this->middleware('permission:productUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:productDelete', ['only' => ['destroy']]);
    }

    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'table');
        $items = ($activeTab === 'reorder') ? Product::with('category')->orderBy('order', 'asc')->get() : [];
        $categories = Category::where('status', true)->orderBy('name', 'asc')->get();
        return view('admin.product.index', compact('activeTab', 'items', 'categories'));
    }

    public function data(Request $request): JsonResponse
    {
        try {
            $query = Product::with('category');

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('sku', 'like', '%' . $request->search . '%')
                      ->orWhereHas('category', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
            }

            $sortColumn = $request->input('sort', 'order');
            $sortDirection = $request->input('direction', 'asc');
            
            if ($sortColumn == 'category') {
                $query->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                      ->orderBy('categories.name', $sortDirection)
                      ->select('products.*');
            } else {
                $query->orderBy($sortColumn, $sortDirection);
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
            Log::error('Failed to fetch products: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve products.'], 500);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = $this->validateProduct($request);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error_modal', 'addModal');
        }
        
        DB::beginTransaction();
        try {
            $data = $validator->validated();
            
            // Handle Image
            if ($request->hasFile('image')) {
                $data['image'] = $this->handleImageUpload($request, new Product(), 'image', 'products', 400, 400); // 400x400
            }

            // Unset package data from main product data
            $packagesData = $data['packages'] ?? [];
            unset($data['packages']);
            
            // Set booleans
            $data['status'] = $data['status'] ?? 0;
            $data['is_top_selling_product'] = $data['is_top_selling_product'] ?? 0;

            $product = Product::create($data);

            // Create packages
            if (!empty($packagesData)) {
                foreach ($packagesData as $package) {
                    $product->packages()->create($package);
                }
            }
            
            DB::commit();
            Log::info('Product created successfully.', ['id' => $product->id, 'name' => $product->name]);
            return redirect()->route('product.index')->with('success','Product created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create product: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create product. Please check logs.']);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            // Eager load packages
            $product = Product::with('packages')->findOrFail($id);
            if ($product->image) {
                $product->image_url = asset($product->image);
            }
            return response()->json($product); 
        } catch (Exception $e) {
             Log::warning("Attempted to fetch non-existent product ID {$id}");
             return response()->json(['error' => 'Product not found.'], 404);
        }
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $validator = $this->validateProduct($request, $id);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput()->with('error_modal_id', $id);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();

            // Handle Image
            if ($request->hasFile('image')) {
                $data['image'] = $this->handleImageUpdate($request, $product, 'image', 'products', 400, 400);
            }

            // Unset package data from main product data
            $packagesData = $data['packages'] ?? [];
            unset($data['packages']);
            
            // Set booleans (checkboxes might not be present if unchecked)
            $data['status'] = $request->has('status') ? 1 : 0;
            $data['is_top_selling_product'] = $request->has('is_top_selling_product') ? 1 : 0;

            $product->update($data);

            // Update packages (Delete old and re-create)
            $product->packages()->delete();
            if (!empty($packagesData)) {
                foreach ($packagesData as $package) {
                    $product->packages()->create($package);
                }
            }
            
            DB::commit();
            Log::info('Product updated successfully.', ['id' => $id]);
            return redirect()->route('product.index')->with('success', 'Product updated successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to update product ID {$id}: " . $e->getMessage());
             return redirect()->back()
                        ->withErrors(['error' => 'Failed to update product.'], 'update') 
                        ->withInput()
                        ->with('error_modal_id', $id);
        }
    }

    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate(['itemIds' => 'required|array']);
        try {
            foreach ($request->itemIds as $index => $id) {
                Product::where('id', $id)->update(['order' => $index + 1]);
            }
            return response()->json(['status' => 'success', 'message' => 'Product order updated successfully.']);
        } catch (Exception $e) { 
            return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500); 
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $product = Product::findOrFail($id);
            
            DB::beginTransaction();
            // Image file will be deleted by Trait if setup, or manually:
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }
            
            $product->delete(); // Packages are deleted by cascade
            DB::commit();

            // Re-order remaining items
            DB::statement('SET @count = 0;');
            DB::update('UPDATE products SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');

            return redirect()->route('product.index')->with('success', 'Product deleted successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete product ID {$id}: " . $e->getMessage());
            return redirect()->route('product.index')->with('error', 'Failed to delete product.');
        }
    }
    
    /**
     * Re-usable validation helper.
     */
    private function validateProduct(Request $request, $id = null)
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'name' => ['required', 'string', 'max:255', $id ? Rule::unique('products')->ignore($id) : Rule::unique('products')],
            'description' => 'nullable|string',
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'sku' => ['nullable', 'string', 'max:100', $id ? Rule::unique('products')->ignore($id) : Rule::unique('products')],
            'stock_quantity' => 'required|integer|min:0',
            'buying_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:selling_price',
            'is_top_selling_product' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            
            // Validation for packages
            'packages' => 'nullable|array',
            'packages.*.variation_name' => 'required_with:packages|string|max:255',
            'packages.*.additional_price' => 'required_with:packages|numeric|min:0',
        ];

        if ($id === null && !$request->hasFile('image')) {
            $rules['image'] = ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048']; // Image required on create
        }

        return Validator::make($request->all(), $rules, [
            'discount_price.lt' => 'The discount price must be less than the selling price.',
            'packages.*.variation_name.required_with' => 'The variation name is required.',
            'packages.*.additional_price.required_with' => 'The additional price is required.',
        ]);
    }
}
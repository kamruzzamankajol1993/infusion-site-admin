<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\ProductVariant;
use App\Models\AssignCategory;
use App\Models\Attribute;
use App\Models\ProductImage;
use App\Models\Stock;
use App\Models\AttributeOption;
use App\Models\ProductAttributeValue;
class ProductController extends Controller
{


     /**
     * NEW: AJAX endpoint to fetch grouped attributes for a specific product for the order form modal.
     */
    public function getAttributesForOrder(Product $product)
    {
        $product->load(['attributeValues.attribute']);

        $attributeIds = $product->attributeValues->pluck('attribute_id');

        $pivotData = DB::table('attribute_category')
            ->where('category_id', $product->category_id)
            ->whereIn('attribute_id', $attributeIds)
            ->pluck('group_name', 'attribute_id');

        $groupedAttributes = $product->attributeValues->groupBy(function ($value) use ($pivotData) {
            return $pivotData[$value->attribute_id] ?? 'General';
        });
        
        $sortedGroupedAttributes = $groupedAttributes->sortBy(function ($attributes, $groupName) {
            $lowerGroupName = strtolower($groupName);
            if ($lowerGroupName === 'general') return 0;
            if ($lowerGroupName === 'other' || $lowerGroupName === 'others') return 2;
            return 1;
        })->sortKeys();


        return response()->json([
            'productName' => $product->name,
            'groupedAttributes' => $sortedGroupedAttributes
        ]);
    }
    private function getProductData()
    {
        return [
            'brands' => Brand::where('status', 1)->get(),
            'categories' => Category::where('status', 1)->get(),
            'fabrics' => Fabric::where('status', 1)->get(),
            'units' => Unit::where('status', 1)->get(),
            'colors' => Color::where('status', 1)->get(),
            'sizes' => Size::where('status', 1)->get(),
            'size_charts' => SizeChart::where('status', 1)->get(),
            'animation_categories' => AnimationCategory::where('status', 1)->get(),
        ];
    }

    // AJAX method to get subcategories
    public function getSubcategories($categoryId)
    {
        return response()->json(Subcategory::where('category_id', $categoryId)->where('status', 1)->get());
    }

    // AJAX method to get sub-subcategories
    public function getSubSubcategories($subcategoryId)
    {
        return response()->json(SubSubcategory::where('subcategory_id', $subcategoryId)->where('status', 1)->get());
    }

    // AJAX method to get size chart entries
    public function getSizeChartEntries($id)
    {
        return response()->json(SizeChart::with('entries')->findOrFail($id));
    }


      /**
     * Display a listing of the products.
     */
    public function index()
    {
        // Pass categories to the view for the filter dropdown
        $categories = Category::where('status', 1)->orderBy('name')->get();
        return view('admin.product.index', compact('categories'));
    }

    /**
     * Fetch data for the AJAX-powered data table.
     */
    public function data(Request $request)
    {
        // Eager load the relationships we need for the table
        $query = Product::with(['category', 'stock', 'images']);

        // Filtering Logic
        if ($request->filled('product_name')) {
            $query->where('name', 'like', $request->product_name . '%');
        }
        if ($request->filled('sku')) {
            $query->where('sku', 'like', $request->sku . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Sorting Logic
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $products = $query->paginate(10);

        return response()->json([
            'data' => $products->items(),
            'total' => $products->total(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
        ]);
    }


     public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.product.create', compact('categories'));
    }

    /**
     * AJAX endpoint to fetch attributes for a given category and its parents.
     */
    /**
     * AJAX endpoint to fetch attributes for a given category and its parents.
     */
    public function getAttributesByCategory(Category $category)
    {
        // Get the selected category's ID and all of its parent IDs
        $categoryIds = array_merge([$category->id], $category->getAllParentIds());
        
        // Fetch all unique attributes assigned to any category in the hierarchy
        $attributes = Attribute::with('options')
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            // Use a join to fetch the group_name directly from the pivot table
            ->leftJoin('attribute_category', function ($join) use ($category) {
                $join->on('attributes.id', '=', 'attribute_category.attribute_id')
                     ->where('attribute_category.category_id', '=', $category->id);
            })
            ->distinct()
            ->select('attributes.*', 'attribute_category.group_name') // Select group_name
            ->get();
            
        return response()->json($attributes);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'category_id' => 'required|exists:categories,id',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'quantity' => 'required|integer|min:0',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'attributes' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // 1. Create the Product
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'buying_price' => $request->buying_price,
                'selling_price' => $request->selling_price,
                'offer_price' => $request->offer_price,
                'sku' => $request->sku,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'status' => $request->status ?? 1,
                'featured' => $request->featured ?? 0,
            ]);

            // 2. Create the Stock record
            $product->stock()->create(['quantity' => $request->quantity]);

            // 3. Handle Image Uploads with Resizing
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $imageName = Str::uuid() . '.' . $imageFile->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/products');
                    if (!File::isDirectory($destinationPath)) {
                        File::makeDirectory($destinationPath, 0777, true, true);
                    }
                    Image::read($imageFile)->cover(600, 600)->save($destinationPath . '/' . $imageName);
                    $product->images()->create(['image_path' => 'uploads/products/' . $imageName]);
                }
            }

            // 👇 **THE CRITICAL FIX IS HERE**
            if ($request->has('attributes')) {

                
                // We must use $request->input('attributes') to get the form data
                foreach ($request->input('attributes') as $attributeId => $submittedValue) {
                    // Skip if the submitted value is null, an empty string, or an empty array from a checkbox
                    if (is_null($submittedValue) || $submittedValue === '' || (is_array($submittedValue) && empty($submittedValue))) {
                        continue;
                    }

                    $attribute = Attribute::find($attributeId);
                    if (!$attribute) continue;

                    $valueToSave = null;

                    switch ($attribute->input_type) {
                        case 'select':
                        case 'radio':
                            $option = AttributeOption::find($submittedValue);
                            if ($option) {
                                $valueToSave = $option->value;
                            }
                            break;
                        case 'checkbox':
                            if (is_array($submittedValue)) {
                                $valueToSave = AttributeOption::whereIn('id', $submittedValue)->pluck('value')->implode(', ');
                            }
                            break;
                        case 'text':
                            $valueToSave = $submittedValue;
                            break;
                    }
//dd($valueToSave);
                    if (!empty($valueToSave)) {
                        ProductAttributeValue::create([
                            'product_id'   => $product->id,
                            'attribute_id' => $attributeId,
                            'value'        => $valueToSave,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('product.index')->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product Creation Failed: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return redirect()->back()->withInput()->with('error', 'Product creation failed. Please check the error log.');
        }
    }
        public function show(Product $product)
    {
        // Eager load all the necessary relationships
        $product->load(['category', 'stock', 'images', 'attributeValues.attribute']);

        // Get all attribute IDs for this product
        $attributeIds = $product->attributeValues->pluck('attribute_id');

        // Run one query to get the group names for these attributes within the product's category
        $pivotData = DB::table('attribute_category')
            ->where('category_id', $product->category_id)
            ->whereIn('attribute_id', $attributeIds)
            ->pluck('group_name', 'attribute_id'); // Returns an array like [attribute_id => group_name]

        // Group the attributeValues collection based on the fetched group names
        $groupedAttributes = $product->attributeValues->groupBy(function ($value) use ($pivotData) {
            // Find the group name from our pivot data, or default to 'General'
            return $pivotData[$value->attribute_id] ?? 'General';
        });

        // --- CUSTOM SORTING LOGIC ---
        $sortedGroupedAttributes = $groupedAttributes->sortBy(function ($attributes, $groupName) {
            $lowerGroupName = strtolower($groupName);
            if ($lowerGroupName === 'general') {
                return 0; // "General" comes first
            }
            if ($lowerGroupName === 'other' || $lowerGroupName === 'others') {
                return 2; // "Other(s)" comes last
            }
            return 1; // All other groups in the middle (will be sorted alphabetically by key later)
        })->sortKeys();

        // Pass both the product and the new sorted/grouped attributes to the view
        return view('admin.product.show', [
            'product' => $product,
            'groupedAttributes' => $sortedGroupedAttributes
        ]);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        // Eager load relationships for the form
        $product->load(['stock', 'images', 'attributeValues']);
        $categories = Category::orderBy('name', 'asc')->get();

        return view('admin.product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'quantity' => 'required|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // New images are not required
            'attributes' => 'nullable|array',
            'delete_images' => 'nullable|array', // Array of image IDs to delete
        ]);

        DB::beginTransaction();
        try {
            // 1. Update the main Product details
            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'buying_price' => $request->buying_price,
                'selling_price' => $request->selling_price,
                'offer_price' => $request->offer_price,
                'sku' => $request->sku,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'status' => $request->status ?? 1,
                'featured' => $request->featured ?? 0,
            ]);

            // 2. Update the Stock record
            $product->stock()->updateOrCreate(['product_id' => $product->id], ['quantity' => $request->quantity]);

            // 3. Handle Image Deletion
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $imageId) {
                    $image = ProductImage::find($imageId);
                    if ($image) {
                        // Delete file from storage
                        $filePath = public_path('uploads/' . $image->image_path);
                        if (File::exists($filePath)) {
                            File::delete($filePath);
                        }
                        // Delete record from database
                        $image->delete();
                    }
                }
            }

            // 4. Handle New Image Uploads with Resizing
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $imageName = Str::uuid() . '.' . $imageFile->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/products');
                    if (!File::isDirectory($destinationPath)) {
                        File::makeDirectory($destinationPath, 0777, true, true);
                    }
                    Image::read($imageFile)->cover(600, 600)->save($destinationPath . '/' . $imageName);
                    $product->images()->create(['image_path' => 'uploads/products/' . $imageName]);
                }
            }

            // 5. Sync Attributes (Delete old ones, then add new ones)
            $product->attributeValues()->delete();
            if ($request->has('attributes')) {
                foreach ($request->input('attributes', []) as $attributeId => $submittedValue) {
                    // This logic is the same as the store method
                    if (is_null($submittedValue) || $submittedValue === '' || (is_array($submittedValue) && empty($submittedValue))) continue;
                    $attribute = Attribute::find($attributeId);
                    if (!$attribute) continue;
                    $valueToSave = null;
                    switch ($attribute->input_type) {
                        case 'select':
                        case 'radio':
                            $option = AttributeOption::find($submittedValue);
                            if ($option) $valueToSave = $option->value;
                            break;
                        case 'checkbox':
                            if (is_array($submittedValue)) $valueToSave = AttributeOption::whereIn('id', $submittedValue)->pluck('value')->implode(', ');
                            break;
                        case 'text':
                            $valueToSave = $submittedValue;
                            break;
                    }
                    if (!empty($valueToSave)) {
                        ProductAttributeValue::create(['product_id' => $product->id, 'attribute_id' => $attributeId, 'value' => $valueToSave]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('product.index')->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product Update Failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update product. Please check the error log.');
        }
    }

     /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Deleting the product will cascade and delete related images, stock, etc.
        // based on database foreign key constraints.
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully.']);
    }
    
    public function ajax_products_delete(Request $request) {
        
       $id = $request->id;
    // Attempt to find the product by its ID
    $product = Product::find($id);

    // Check if the product exists. If not, return a 404 Not Found response.
    if (!$product) {
        return response()->json(['message' => 'Product not found.'], 404);
    }

    // Use a database transaction to ensure all operations succeed or fail together.
    DB::transaction(function () use ($product) {
        // Delete images for each product variant
        foreach ($product->variants as $variant) {
            $this->deleteImage($variant->variant_image);
        }

        // Delete the main and thumbnail images
        $this->deleteImage($product->thumbnail_image);
        $this->deleteImage($product->main_image);

        // Delete the product record itself, but only after its images are successfully deleted
        $product->delete();
    });

    // If the transaction completes without errors, return a success message.
    return response()->json(['message' => 'Product deleted successfully.']);
}

    private function uploadImage($image, $directory)
    {
        $imageName = Str::uuid() . '.' . 'webp';
        $destinationPath = public_path('uploads/' . $directory);
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        Image::read($image->getRealPath())->resize(600, 600, function ($c) {
            $c->aspectRatio(); $c->upsize();
        })->save($destinationPath . '/' . $imageName);
        return $directory . '/' . $imageName;
    }

    private function uploadImageMobile($image, $directory)
    {
        $imageName = Str::uuid() . '.' . 'webp';
        $destinationPath = public_path('uploads/' . $directory);
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        Image::read($image->getRealPath())->resize(300, 300, function ($c) {
            $c->aspectRatio(); $c->upsize();
        })->save($destinationPath . '/' . $imageName);
        return $directory . '/' . $imageName;
    }

    private function deleteImage($paths)
    {
        if (is_array($paths)) {
            foreach ($paths as $path) {
                if ($path && File::exists(public_path('uploads/' . $path))) {
                    File::delete(public_path('uploads/' . $path));
                }
            }
        } elseif (is_string($paths)) {
            if ($paths && File::exists(public_path('uploads/' . $paths))) {
                File::delete(public_path('uploads/' . $paths));
            }
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingCategory; // Import the model
use App\Traits\ImageUploadTrait; // Import the trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // For deleting file
use Exception;
use Illuminate\Support\Facades\DB;


class TrainingCategoryController extends Controller
{
    use ImageUploadTrait; // Use the image upload trait

    public function __construct()
    {
         // Adjust permission names as needed
         $this->middleware('permission:trainingCategoryView|trainingCategoryAdd|trainingCategoryUpdate|trainingCategoryDelete', ['only' => ['index','data']]);
         $this->middleware('permission:trainingCategoryAdd', ['only' => ['store']]);
         $this->middleware('permission:trainingCategoryUpdate', ['only' => ['show', 'update']]);
         $this->middleware('permission:trainingCategoryDelete', ['only' => ['destroy']]);
    }

    public function index(): View
    {
        return view('admin.training_category.index');
    }

    public function data(Request $request): JsonResponse
    {
        try {
            $query = TrainingCategory::query();

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            $allowedSorts = ['id', 'name', 'created_at'];
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('id', 'desc');
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
            Log::error('Failed to fetch training categories: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve categories.'], 500);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:training_categories,name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // Max 1MB
        ]);

        DB::beginTransaction();
        try {
            $categoryData = $request->only('name');

            $tempModel = new TrainingCategory();
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'training_categories', 750, 422); // Use trait
            if ($imagePath) {
                $categoryData['image'] = $imagePath;
            } else {
                 throw new Exception("Category image upload failed or missing.");
            }

            TrainingCategory::create($categoryData);
            DB::commit();

            Log::info('Training Category created successfully.', ['name' => $request->name]);
            return redirect()->route('trainingCategory.index')->with('success','Training Category created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create training category: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to create category. Please check logs.']);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $category = TrainingCategory::findOrFail($id);
            if ($category->image) {
                $category->image_url = asset($category->image);
            } else {
                $category->image_url = null;
            }
            return response()->json($category);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to fetch non-existent training category ID {$id}");
             return response()->json(['error' => 'Category not found.'], 404);
        } catch (Exception $e) {
            Log::error("Failed to fetch training category ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve category data.'], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $category = TrainingCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:training_categories,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // Nullable
        ]);

        DB::beginTransaction();
        try {
            $categoryData = $request->only('name');

            $imagePath = $this->handleImageUpdate($request, $category, 'image', 'training_categories', 750, 422); // Use trait
            $categoryData['image'] = $imagePath;

            $category->update($categoryData);
            DB::commit();

            Log::info('Training Category updated successfully.', ['id' => $id]);
            $updatedImageUrl = $category->image ? asset($category->image) : null;
            return response()->json(['message' => 'Category updated successfully', 'image_url' => $updatedImageUrl]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to update training category ID {$id}: " . $e->getMessage());
             return response()->json(['error' => 'Failed to update category.'], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $category = TrainingCategory::findOrFail($id);
            DB::beginTransaction();

            if ($category->image && File::exists(base_path($category->image))) {
                File::delete(base_path($category->image));
            }

            $category->delete();
            DB::commit();

            return response()->json(['message' => 'Category deleted successfully.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             DB::rollBack();
             Log::warning("Attempted to delete non-existent training category ID {$id}");
             return response()->json(['error' => 'Category not found.'], 404);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete training category ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete category.'], 500);
        }
    }
}
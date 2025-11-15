<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.category.index', compact('categories'));
    }

    public function data(Request $request)
    {
        try {
            $query = Category::with('parents');

            if ($request->filled('search')) {
                $query->where('name', 'like', $request->search . '%');
            }

            $sort = $request->get('sort', 'id');
            $direction = $request->get('direction', 'desc');
            $query->orderBy($sort, $direction);

            $categories = $query->paginate(10);

            return response()->json([
                'data' => $categories->items(),
                'total' => $categories->total(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to fetch category data: ' . $e);
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::with('parents')->findOrFail($id);
            $category->parent_ids = $category->parents->pluck('id');
            return response()->json($category);
        } catch (Exception $e) {
            Log::error("Failed to show category ID {$id}: " . $e);
            return response()->json(['error' => 'Category not found.'], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name',
            'parent_ids' => 'nullable|array',
            'parent_ids.*' => 'exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        try {
            $path = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'anim_cat_'.time().'.webp';
                $destinationPath = public_path('uploads/categories');

                if (!File::isDirectory($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }

                Image::read($image->getRealPath())->resize(50, 50, function ($c) {
                    $c->aspectRatio(); $c->upsize();
                })->toWebp()->save($destinationPath.'/'.$imageName);
                $path = 'uploads/categories/'.$imageName;
            }

            $category = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                 'is_featured' => $request->boolean('is_featured'),
                'image' => $path,
            ]);

            if ($request->filled('parent_ids')) {
                $category->parents()->attach($request->parent_ids);
            }

            Log::info('Category created successfully.', ['id' => $category->id, 'name' => $category->name]);
            return redirect()->back()->with('success', 'Category created successfully!');

        } catch (Exception $e) {
            Log::error('Failed to create category: ' . $e);
            return redirect()->back()->with('error', 'Failed to create category. Please check logs.')->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $request->validate([
                'name' => 'required|string|unique:categories,name,' . $category->id,
                'parent_ids' => 'nullable|array',
                'parent_ids.*' => 'exists:categories,id',
                'image' => 'nullable|image|max:2048',
            ]);

            $path = $category->image;
            if ($request->hasFile('image')) {
                if ($category->image && File::exists(public_path($category->image))) {
                    File::delete(public_path($category->image));
                }
                $image = $request->file('image');
                $imageName = 'anim_cat_'.time().'.webp';
                $destinationPath = public_path('uploads/categories');

                if (!File::isDirectory($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }

                Image::read($image->getRealPath())->resize(50, 50, function ($c) {
                    $c->aspectRatio(); $c->upsize();
                })->toWebp()->save($destinationPath.'/'.$imageName);
                $path = 'uploads/categories/'.$imageName;
            }

            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'status' => $request->status,
                'image' => $path,
                 'is_featured' => $request->boolean('is_featured'),
            ]);
            
            $category->parents()->sync($request->parent_ids ?? []);

            Log::info('Category updated successfully.', ['id' => $id]);
            return response()->json(['message' => 'Category updated successfully']);

        } catch (Exception $e) {
            Log::error("Failed to update category ID {$id}: " . $e);
            return response()->json(['error' => 'Failed to update category.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            $category->parents()->detach();
            $category->children()->detach();
            $category->delete();

            Log::info('Category deleted successfully.', ['id' => $id]);
            return redirect()->route('category.index')->with('success', 'Category deleted successfully!');
        } catch (Exception $e) {
            Log::error("Failed to delete category ID {$id}: " . $e);
            return redirect()->route('category.index')->with('error', 'Failed to delete category.');
        }
    }

    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $categories = Category::whereIn('id', $request->ids)->get();

                foreach ($categories as $category) {
                    if ($category->image && File::exists(public_path($category->image))) {
                        File::delete(public_path($category->image));
                    }
                    $category->parents()->detach();
                    $category->children()->detach();
                }

                Category::whereIn('id', $request->ids)->delete();
            });

            Log::info('Multiple categories deleted successfully.', ['ids' => $request->ids]);
            return response()->json(['message' => 'Selected categories have been deleted successfully!']);
        } catch (Exception $e) {
            Log::error('Failed to delete multiple categories: ' . $e);
            return response()->json(['error' => 'Failed to delete categories.'], 500);
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class HeroSectionController extends Controller
{
    public function index()
    {
        try {
            $heroSection = HeroSection::first();
            return view('admin.hero_section.index', compact('heroSection'));
        } catch (Exception $e) {
            Log::error('Failed to load hero section page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load the page.');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'existing_left_images' => 'nullable|array',
            'existing_left_images.*' => 'string',
            'new_left_images' => 'nullable|array',
            'new_left_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'top_right_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'bottom_right_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $newlyUploadedPaths = [];

        try {
            DB::beginTransaction();

            $heroSection = HeroSection::firstOrCreate(['id' => 1]);
            $data = [];

            // === START: Multiple Image Logic ===
            $currentImages = $heroSection->left_image ?? [];
            $keptImages = $request->input('existing_left_images', []);
            $imagesToDelete = array_diff($currentImages, $keptImages);

            $newImagePaths = [];
            if ($request->hasFile('new_left_images')) {
                foreach ($request->file('new_left_images') as $file) {
                    $path = $this->uploadImage($file, 'hero', 950, 450);
                    $newImagePaths[] = $path;
                    $newlyUploadedPaths[] = $path; // Track for potential rollback
                }
            }

            // Combine kept images with newly uploaded ones
            $data['left_image'] = array_merge($keptImages, $newImagePaths);
            // === END: Multiple Image Logic ===


            // Handle single top right image
            if ($request->hasFile('top_right_image')) {
                if ($heroSection->top_right_image) {
                    $imagesToDelete[] = $heroSection->top_right_image;
                }
                $path = $this->uploadImage($request->file('top_right_image'), 'hero', 465, 222);
                $data['top_right_image'] = $path;
                $newlyUploadedPaths[] = $path;
            }

            // Handle single bottom right image
            if ($request->hasFile('bottom_right_image')) {
                if ($heroSection->bottom_right_image) {
                    $imagesToDelete[] = $heroSection->bottom_right_image;
                }
                $path = $this->uploadImage($request->file('bottom_right_image'), 'hero', 465, 222);
                $data['bottom_right_image'] = $path;
                $newlyUploadedPaths[] = $path;
            }

            // Update the database record
            $heroSection->update($data);

            DB::commit();

            // After successful commit, delete the old images
            foreach ($imagesToDelete as $oldImage) {
                $this->deleteImage($oldImage);
            }

            Log::info('Hero section updated successfully.');
            return redirect()->back()->with('success', 'Hero section updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();

            // Delete any newly uploaded files if the transaction failed
            foreach ($newlyUploadedPaths as $path) {
                $this->deleteImage($path);
            }

            Log::error('Failed to update hero section: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update hero section. Please check logs.');
        }
    }

    private function uploadImage($image, $directory, $width, $height)
    {
        $imageName = Str::uuid() . '.webp';
        $destinationPath = public_path('uploads/' . $directory);

        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }

        Image::read($image->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($destinationPath . '/' . $imageName);

        // Return path relative to the 'uploads' directory
        return $directory . '/' . $imageName;
    }

    private function deleteImage($path)
    {
        if ($path && File::exists(public_path('uploads/' . $path))) {
            File::delete(public_path('uploads/' . $path));
        }
    }
}
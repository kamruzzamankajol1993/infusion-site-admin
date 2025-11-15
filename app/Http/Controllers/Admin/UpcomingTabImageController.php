<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UpcomingTabImage;
use App\Traits\ImageUploadTrait; // Using the trait from your other controllers
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Exception;

class UpcomingTabImageController extends Controller
{
    use ImageUploadTrait; // Use the image trait

    /**
     * Display the form to create or update the image.
     */
    public function index(): View
    {
        // Find the first (and only) record.
        $imageRecord = UpcomingTabImage::first();
        return view('admin.upcomingTabImage.index', compact('imageRecord'));
    }

    /**
     * Store the new image or update the existing one.
     */
    public function storeOrUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => [
                'required', // Make it required since this is the only field
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048', // Max 2MB
            ],
        ]);

        // Specific dimensions from your request
        $width = 2193;
        $height = 2761;

        try {
            // Find the first record, or create a new instance if none exists
            // Using firstOrCreate ensures we only ever have one row
            $imageRecord = UpcomingTabImage::firstOrCreate(['id' => 1]);

            // Use the trait's handleImageUpdate method
            // This will upload a new file, replace the old file,
            // and resize it according to your dimensions.
            $imagePath = $this->handleImageUpdate(
                $request,
                $imageRecord,  // Pass the existing model instance
                'image',        // Field name
                'upcoming_tab', // Directory in public/uploads/
                $width,         // Target width
                $height        // Target height
            );
            
            // handleImageUpdate returns the path, so we save it
            $imageRecord->image = $imagePath;
            $imageRecord->save();
            
            Log::info('Upcoming Tab Image updated successfully.');
            return redirect()->back()->with('success', 'Image updated successfully!');

        } catch (Exception $e) {
            Log::error('Failed to update Upcoming Tab Image: Read Me' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update image. ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BreadCrumbImage;
use App\Traits\ImageUploadTrait; // <-- 1. Import the trait
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Exception;
use Intervention\Image\Laravel\Facades\Image;
class BreadCrumbImageController extends Controller
{
    // --- 2. Use the trait ---
    use ImageUploadTrait;

    // --- 3. Add permission middleware ---
    public function __construct()
    {
         // Adjust permission names as needed
         $this->middleware('permission:breadCrumbImageView', ['only' => ['index']]);
         $this->middleware('permission:breadCrumbImageUpdate', ['only' => ['storeOrUpdate']]);
    }

    // --- 4. Updated Index Method ---
    public function index()
    {
        // This list defines all sections that will appear on the form
        $types = [
            'about_us' => 'About Us',
            'board_of_directors' => 'Board of Directors',
            'subscriber_members' => 'Subscriber Members',
            'officers' => 'Officers',
            'past_chairmen' => 'Past Chairmen',
            'past_managing_directors' => 'Past Managing Directors',
            'service' => 'Service',
            'all_projects' => 'All Projects',
            'complete_projects' => 'Complete Projects',
            'ongoing_projects' => 'Ongoing Projects',
            'project_details' => 'Project Details',
            'upcoming_training' => 'Upcoming Training',
            'all_training' => 'All Training',
            'training_details' => 'Training Details',
            'career' => 'Career',
            'career_detail' => 'Career Detail',
            'publications' => 'Publications',
            'press_releases' => 'Press Releases',
            'press_release_details' => 'Press Release Details',
            'events' => 'Events',
            'event_details' => 'Event Details',
            'gallery' => 'Gallery',
            'download' => 'Download',
            'notice' => 'Notice',
            'clients' => 'Clients', // Added Clients
            'contact' => 'Contact', // Added Contact
            'countries' => 'Countries of Operation',
        ];

        // Get all existing images from DB, keyed by their 'type' for easy lookup
        $images = BreadCrumbImage::all()->keyBy('type');
        
        return view('admin.bread_crumb_image.index', compact('images', 'types'));
    }

    // --- 5. New Method to handle the batch update ---
    public function storeOrUpdate(Request $request)
    {
        // Validation
        $request->validate([
            'data' => 'required|array',
            'data.*.name' => 'required|string|max:255',
            'data.*.logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB Max
        ]);

        $allData = $request->input('data');
        $allFiles = $request->file('data'); // This will be a nested array

        DB::beginTransaction();
        try {
            foreach ($allData as $type => $data) {
                // Find existing or create new model instance
                $model = BreadCrumbImage::firstOrNew(['type' => $type]);
                $model->name = $data['name'];

                // Check if a new file was uploaded for this type
                if (isset($allFiles[$type]['logo']) && $allFiles[$type]['logo']->isValid()) {
                    
                    $file = $allFiles[$type]['logo'];

                    // 1. Delete old file if it exists
                    if ($model->logo && File::exists(public_path($model->logo))) {
                        File::delete(public_path($model->logo));
                    }

                    // 2. Upload new file (using logic adapted from your Trait)
                    // We can't use the trait directly because of the nested array
                    $imageName = $type . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = 'uploads/breadcrumb_images/';
                    $fullPath = public_path($path);

                    if (!File::isDirectory($fullPath)) {
                        File::makeDirectory($fullPath, 0755, true);
                    }

                    // Resize to 764x430 (fixed) and save
                    Image::read($file)->resize(764, 430)->save($fullPath . $imageName);

                    $model->logo = $path . $imageName;
                }
                
                $model->save();
            }

            DB::commit();
            return redirect()->route('breadCrumbImage.index')->with('success', 'Breadcrumb images updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update breadcrumb images: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred. Please check logs.');
        }
    }
}
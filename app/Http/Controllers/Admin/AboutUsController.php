<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Traits\ImageUploadTrait; // Import the trait
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // For deleting file
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;


class AboutUsController extends Controller
{
    use ImageUploadTrait; // Use the trait

    /**
     * Display the About Us management page.
     */
    public function index(): View
    {
        $aboutUs = AboutUs::firstOrNew([]);
        return view('admin.about_us.index', compact('aboutUs'));
    }

    /**
     * Store the newly created About Us content.
     */
    public function store(Request $request): RedirectResponse
    {
        if (AboutUs::count() > 0) {
            return redirect()->route('aboutUs.index')->with('error', 'About Us content already exists. Please edit the existing content.');
        }

        $validatedData = $request->validate([
            'our_story' => 'required|string',
            'team_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:1024', // 600x400
            'mission' => 'required|string',
            'vision' => 'required|string',
            'mission_vision_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:1024', // 600x400
            'founder_quote' => 'required|string',
            'founder_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:1024', // 400x500
            'founder_name' => 'required|string|max:255',
            'founder_designation' => 'required|string|max:255',
            'trade_license' => 'required|string|max:255',
            'bin' => 'required|string|max:255',
            'tin' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $aboutData = $validatedData;
            $tempModel = new AboutUs(); // For trait

            // Handle Team Image (600x400)
            $teamImagePath = $this->handleImageUpload($request, $tempModel, 'team_image', 'about_us', 600, 400);
            $aboutData['team_image'] = $teamImagePath ?? throw new Exception("Team Image upload failed.");

            // Handle Mission/Vision Image (600x400)
            $mvImagePath = $this->handleImageUpload($request, $tempModel, 'mission_vision_image', 'about_us', 600, 400);
            $aboutData['mission_vision_image'] = $mvImagePath ?? throw new Exception("Mission/Vision Image upload failed.");

            // Handle Founder Image (400x500)
            $founderImagePath = $this->handleImageUpload($request, $tempModel, 'founder_image', 'about_us', 400, 500);
            $aboutData['founder_image'] = $founderImagePath ?? throw new Exception("Founder Image upload failed.");


            AboutUs::create($aboutData);
            DB::commit();

            Log::info('About Us content created successfully.');
            return redirect()->route('aboutUs.index')->with('success', 'About Us content saved successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create About Us content: ' ->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to save content. Please check logs.']);
        }
    }


    /**
     * Update the existing About Us content.
     */
    public function update(Request $request, AboutUs $aboutUs): RedirectResponse
    {
        $validatedData = $request->validate([
            'our_story' => 'required|string',
            'team_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 600x400
            'mission' => 'required|string',
            'vision' => 'required|string',
            'mission_vision_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 600x400
            'founder_quote' => 'required|string',
            'founder_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 400x500
            'founder_name' => 'required|string|max:255',
            'founder_designation' => 'required|string|max:255',
            'trade_license' => 'required|string|max:255',
            'bin' => 'required|string|max:255',
            'tin' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $aboutData = $validatedData;

            // Handle Team Image Update (600x400)
            $aboutData['team_image'] = $this->handleImageUpdate($request, $aboutUs, 'team_image', 'about_us', 600, 400);

            // Handle Mission/Vision Image Update (600x400)
            $aboutData['mission_vision_image'] = $this->handleImageUpdate($request, $aboutUs, 'mission_vision_image', 'about_us', 600, 400);

            // Handle Founder Image Update (400x500)
            $aboutData['founder_image'] = $this->handleImageUpdate($request, $aboutUs, 'founder_image', 'about_us', 400, 500);

            $aboutUs->update($aboutData);
            DB::commit();

            Log::info('About Us content updated successfully.', ['id' => $aboutUs->id]);
            return redirect()->route('aboutUs.index')->with('success', 'About Us content updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update About Us content ID ' . $aboutUs->id . ': ' . $e->getMessage());
             return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to update content. Please check logs.']);
        }
    }
}
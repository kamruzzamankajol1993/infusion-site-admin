<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemInformation;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Cache;
class SystemInformationController extends Controller
{
    /**
     * Set permissions for the controller methods.
     */
    function __construct()
    {
         $this->middleware('permission:panelSettingView', ['only' => ['index']]);
         $this->middleware('permission:panelSettingUpdate', ['only' => ['update']]);
    }

    /**
     * Display the system settings page.
     * Finds the first setting record or creates a new one if none exist.
     */
    public function index(): View
    {
        try {
            $settings = SystemInformation::firstOrCreate([], ['ins_name' => 'Default Site Name']);
            return view('admin.panelSettingInfo.index', compact('settings'));
        } catch (Exception $e) {
            Log::error('Failed to load system information index page: ' . $e->getMessage());
            // Redirect to a general error page or dashboard with an error
            return redirect()->route('admin.dashboard')->with('error', 'Could not load settings.');
        }
    }

    /**
     * Update the system settings.
     */
   public function update(Request $request, $id): RedirectResponse
    {
        // This validation block is correct.
       // --- MODIFICATION: Made all text/url fields required ---
        $request->validate([
            'ins_name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|string|email',
            'front_url' => 'required|string|url',
            'description' => 'required|string',     // Changed
            'develop_by' => 'required|string',    // Changed
            'main_url' => 'required|string|url',      // Changed

            // Other fields
            'phone_two' => 'nullable|string',
            'address_two' => 'nullable|string',
            'email_two' => 'nullable|email',

            // Images remain nullable on update (doesn't force re-upload)
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
            'rectangular_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
        // --- END MODIFICATION ---

        try {
            DB::beginTransaction();

            $systemInformation = SystemInformation::findOrFail($id);
            $systemInformation->fill($request->except(['logo', 'icon', 'rectangular_logo']));

            // Process image uploads
            $this->processImageUpload($request, $systemInformation, 'logo', 'logo', 316, 316);
            $this->processImageUpload($request, $systemInformation, 'icon', 'icon', 50, 50);
            $this->processImageUpload($request, $systemInformation, 'rectangular_logo', 'rect_logo', 1380, 298);

            $systemInformation->save();
            DB::commit();

            // Clear the cache so settings update immediately
            Cache::forget('system_settings');

            Log::info('System Information updated successfully.', ['id' => $systemInformation->id]);
            return redirect()->route('systemInformation.index')->with('success', 'Updated Successfully');
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to update system information for ID {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update information. Please check the logs.');
        }
    }

    /**
     * Private helper method to process image uploads.
     */
    private function processImageUpload(Request $request, SystemInformation $model, string $field, string $prefix, ?int $width, ?int $height): void
    {
        if ($request->hasFile($field)) {
            $productImage = $request->file($field);
            $time_dy = time() . date("Ymd");
            $imageName = $prefix . '_' . $time_dy . '.' . $productImage->getClientOriginalExtension();
            $directory = 'public/uploads/';
            $imageUrl = $directory . $imageName;

            $img = Image::read($productImage);

            if ($width && $height) {
                $img->resize($width, $height);
            }
            
            $img->save(base_path($imageUrl)); // Use base_path for accurate saving
            $model->{$field} = $imageUrl;
        }
    }
}
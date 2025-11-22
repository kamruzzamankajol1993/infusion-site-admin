<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VpsPage;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VpsPageController extends Controller
{
    use ImageUploadTrait;
    
    public function __construct() {
         $this->middleware('permission:vpsPageView', ['only' => ['index', 'storeOrUpdate']]); 
    }

    public function index(): View {
        $content = VpsPage::firstOrNew([]);
        // Set default features if empty
        if (empty($content->hero_features)) {
            $content->hero_features = ["Dedicated IP Address", "Dedicated CPU & RAM", "Unmetered Bandwidth", "Any Windows OS"];
        }
        return view('admin.vps_page.index', compact('content'));
    }

    public function storeOrUpdate(Request $request): RedirectResponse {
        $validatedData = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_features' => 'required|array',
            'hero_features.*' => 'required|string|max:255',
            'hero_button_text' => 'required|string|max:100',
            'hero_button_link' => 'required|string|max:255',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 500x400
            'category_1_title' => 'required|string|max:255',
            'category_2_title' => 'required|string|max:255',
            'category_3_title' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // 1. FIX: Use firstOrNew instead of firstOrCreate
            $content = VpsPage::firstOrNew([]);
            
            $data = $validatedData;
            
            // 2. Handle Image
            // We pass the $content object (even if new) to the trait.
            $data['hero_image'] = $this->handleImageUpdate($request, $content, 'hero_image', 'vps_page', 500, 400);
            
            // 3. Fill and Save manually
            $content->fill($data);
            $content->save();
            
            DB::commit();
            Log::info('VPS Page content updated successfully.');
            return redirect()->route('vpsPage.page.index')->with('success', 'Content updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update VPS Page content: ' . $e->getMessage());
             return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update content. Please check logs.']);
        }
    }
}
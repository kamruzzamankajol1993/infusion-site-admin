<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalMarketingPage;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;

class DigitalMarketingPageController extends Controller
{
    use ImageUploadTrait;

    public function __construct()
    {
         // *** IMPORTANT: Create this permission ***
         $this->middleware('permission:digitalMarketingPageView', ['only' => ['index', 'storeOrUpdate']]);
    }

    public function index(): View
    {
        $content = DigitalMarketingPage::firstOrNew([]);
        return view('admin.digital_marketing_page.index', compact('content'));
    }

    public function storeOrUpdate(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'required|string',
            'hero_button_text' => 'required|string|max:100',
            
            'intro_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 600x450
            'intro_title' => 'required|string|max:255',
            'intro_description' => 'required|string',
            'intro_button_text' => 'required|string|max:100',

            'consultant_title' => 'required|string|max:255',
            'consultant_description' => 'required|string',
            'consultant_button_text' => 'required|string|max:100',

            'growth_title' => 'required|string|max:255',
            'growth_description' => 'required|string',
            
            'solutions_subtitle' => 'required|string|max:100',
            'solutions_title' => 'required|string|max:255',
            'solutions_description' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $content = DigitalMarketingPage::firstOrCreate([]);
            $data = $validatedData;

            // Handle Intro Image (600x450)
            $data['intro_image'] = $this->handleImageUpdate($request, $content, 'intro_image', 'digital_marketing', 600, 450);

            $content->update($data);
            DB::commit();

            Log::info('Digital Marketing Page content updated successfully.');
            return redirect()->route('digitalMarketingPage.index')->with('success', 'Content updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update Digital Marketing Page content: ' . $e->getMessage());
             return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update content. Please check logs.']);
        }
    }
}
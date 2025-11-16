<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UkCompanyPage;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UkCompanyPageController extends Controller
{
    use ImageUploadTrait;
    public function __construct() {
         $this->middleware('permission:ukCompanyPageView', ['only' => ['index', 'storeOrUpdate']]); // Create this permission
    }

    public function index(): View {
        $content = UkCompanyPage::firstOrNew([]);
        return view('admin.uk_company_page.index', compact('content'));
    }

    public function storeOrUpdate(Request $request): RedirectResponse {
        $validatedData = $request->validate([
            'hero_subtitle_top' => 'required|string|max:255',
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'required|string',
            'hero_button_text' => 'required|string|max:100',
            'hero_button_link' => 'required|string|max:255',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 400x450
            'carbon_badge_text' => 'required|string|max:255',
            'pricing_title' => 'required|string|max:255',
            'pricing_description' => 'required|string',
            'testimonial_title' => 'required|string|max:255',
            'review_title' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $content = UkCompanyPage::firstOrCreate([]);
            $data = $validatedData;
            $data['hero_image'] = $this->handleImageUpdate($request, $content, 'hero_image', 'uk_company', 400, 450);
            $content->update($data);
            DB::commit();
            Log::info('UK Company Page content updated successfully.');
            return redirect()->route('ukCompany.page.index')->with('success', 'Content updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update UK Company Page content: ' . $e->getMessage());
             return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update content. Please check logs.']);
        }
    }
}
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebSolutionPage;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebSolutionPageController extends Controller
{
    use ImageUploadTrait;

    public function __construct() {
         $this->middleware('permission:webSolutionPageView', ['only' => ['index', 'storeOrUpdate']]); // Create this permission
    }

    public function index(): View {
        $content = WebSolutionPage::firstOrNew([]);
        return view('admin.web_solution_page.index', compact('content'));
    }

    public function storeOrUpdate(Request $request): RedirectResponse {
        $validatedData = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'required|string',
            'hero_button_text' => 'required|string|max:100',
            'intro_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 600x450
            'intro_title' => 'required|string|max:255',
            'intro_description' => 'required|string',
            'intro_button_text' => 'required|string|max:100',
            'pro_title' => 'required|string|max:255',
            'pro_description' => 'required|string',
            'pro_button_text' => 'required|string|max:100',
            'checklist_title' => 'required|string|max:255',
            'checklist_description' => 'required|string',
            'includes_subtitle' => 'required|string|max:100',
            'includes_title' => 'required|string|max:255',
            'includes_description' => 'required|string',
            'providing_subtitle' => 'required|string|max:100',
            'providing_title' => 'required|string|max:255',
            'providing_description' => 'required|string',
            'work_subtitle' => 'required|string|max:100',
            'work_title' => 'required|string|max:255',
            'work_description' => 'required|string',
            'cta_title' => 'required|string|max:255',
            'cta_description' => 'required|string',
            'cta_button_text' => 'required|string|max:100',
            'care_subtitle' => 'required|string|max:100',
            'care_title' => 'required|string|max:255',
            'care_description' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $content = WebSolutionPage::firstOrCreate([]);
            $data = $validatedData;
            $data['intro_image'] = $this->handleImageUpdate($request, $content, 'intro_image', 'web_solution', 600, 450);
            $content->update($data);
            DB::commit();
            Log::info('Web Solution Page content updated successfully.');
            return redirect()->route('webSolution.page.index')->with('success', 'Content updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update Web Solution Page content: ' . $e->getMessage());
             return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update content. Please check logs.']);
        }
    }
}
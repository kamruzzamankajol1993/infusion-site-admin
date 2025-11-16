<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacebookPage;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;

class FacebookPageController extends Controller
{
    use ImageUploadTrait;

    public function __construct()
    {
         // *** IMPORTANT: Create this permission ***
         $this->middleware('permission:facebookPageView', ['only' => ['index', 'storeOrUpdate']]);
    }

    public function index(): View
    {
        $content = FacebookPage::firstOrNew([]);
        return view('admin.facebook_page.index', compact('content'));
    }

    public function storeOrUpdate(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'header_title' => 'required|string|max:255',
            'intro_title' => 'required|string|max:255',
            'intro_description' => 'required|string',
            'intro_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 500x300
            'pricing_title' => 'required|string|max:255',
            'pricing_description' => 'required|string',
            'more_services_title' => 'required|string|max:255',
            'more_services_description' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $content = FacebookPage::firstOrCreate([]);
            $data = $validatedData;

            // Handle Intro Image (500x300)
            $data['intro_image'] = $this->handleImageUpdate($request, $content, 'intro_image', 'facebook_page', 500, 300);

            $content->update($data);
            DB::commit();

            Log::info('Facebook Page content updated successfully.');
            return redirect()->route('facebookPage.page.index')->with('success', 'Content updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update Facebook Page content: ' . $e->getMessage());
             return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update content. Please check logs.']);
        }
    }
}
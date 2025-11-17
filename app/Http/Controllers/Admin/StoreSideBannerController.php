<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSideBanner;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreSideBannerController extends Controller
{
    use ImageUploadTrait;

    public function __construct() {
         $this->middleware('permission:storeSideBannerView', ['only' => ['index', 'storeOrUpdate']]);
    }

    public function index(): View {
        $content = StoreSideBanner::firstOrNew([]);
        return view('admin.store_side_banner.index', compact('content'));
    }

    public function storeOrUpdate(Request $request): RedirectResponse {
        $validatedData = $request->validate([
            'top_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 400x200
            'top_link' => 'required|string|max:255',
            'bottom_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // 400x200
            'bottom_link' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $content = StoreSideBanner::firstOrCreate([]);
            $data = $validatedData;

            // Handle Top Image
            $data['top_image'] = $this->handleImageUpdate($request, $content, 'top_image', 'store_banners', 400, 200);
            // Handle Bottom Image
            $data['bottom_image'] = $this->handleImageUpdate($request, $content, 'bottom_image', 'store_banners', 400, 200);

            $content->update($data);
            DB::commit();

            Log::info('Store Side Banners updated successfully.');
            return redirect()->route('storeSideBanner.index')->with('success', 'Banners updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update side banners: ' . $e->getMessage());
             return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update banners. Please check logs.']);
        }
    }
}
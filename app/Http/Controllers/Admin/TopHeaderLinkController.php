<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopHeaderLink;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Cache;
class TopHeaderLinkController extends Controller
{
    /**
     * Add permissions middleware.
     */
    public function __construct()
    {
         // Adjust permission names as needed
         $this->middleware('permission:headerLinkView', ['only' => ['index']]);
         $this->middleware('permission:headerLinkUpdate', ['only' => ['storeOrUpdate']]);
    }

    /**
     * Display the form to manage the two header links.
     */
    public function index(): View
    {
        // Find links with ID 1 and 2, or create new instances if they don't exist
        $link1 = TopHeaderLink::findOrNew(1);
        $link2 = TopHeaderLink::findOrNew(2);
        
        return view('admin.top_header_link.index', compact('link1', 'link2'));
    }

    /**
     * Store or update the two header links.
     */
    public function storeOrUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            // Rules for Link 1
            'link1_title' => 'required|string|max:255',
            'link1_link'  => 'nullable|url|max:255',
            
            // Rules for Link 2
            'link2_title' => 'nullable|string|max:255',
            'link2_link'  => 'nullable|url|max:255',
        ]);

        try {
            // Update or create Link 1 (ID 1)
            TopHeaderLink::updateOrCreate(
                ['id' => 1],
                [
                    'title' => $request->link1_title,
                    'link'  => $request->link1_link
                ]
            );

            // Update or create Link 2 (ID 2)
            TopHeaderLink::updateOrCreate(
                ['id' => 2],
                [
                    'title' => $request->link2_title,
                    'link'  => $request->link2_link
                ]
            );
// --- ADD THIS LINE ---
            Cache::forget('top_header_links');
            // --- END ADD ---
            Log::info('Top Header Links updated successfully.');
            return redirect()->back()->with('success', 'Links updated successfully!');

        } catch (Exception $e) {
            Log::error('Failed to update Top Header Links: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update links. ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExtraPage;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\View\View; // Import View
use Illuminate\Http\RedirectResponse; // Import RedirectResponse

class ExtraPageController extends Controller
{
    // --- UPDATED: Simplified validation ---
    private function validateRequest(Request $request)
    {
        // Only validate the remaining fields
        return $request->validate([
            'privacy_policy'  => 'nullable|string',
            'term_condition'  => 'nullable|string',
            'faq'             => 'nullable|string',
        ]);
    }

    public function index(): View // Added return type hint
    {
        try {
            // Use firstOrNew to handle the case where the record doesn't exist yet
            $extraPage = ExtraPage::firstOrNew([]);
            return view('admin.extraPage.manage', compact('extraPage'));
        } catch (Exception $e) {
            Log::error('Failed to load extra pages content: ' . $e->getMessage()); // Use getMessage()
            return redirect()->route('home')->with('error', 'Could not load page content.'); // Redirect home on error
        }
    }

    public function store(Request $request): RedirectResponse // Added return type hint
    {
        // Prevent creating multiple records
        if (ExtraPage::count() > 0) {
            return redirect()->route('extraPage.index')->with('error', 'Page content already exists. Please edit the existing content.');
        }

        $validatedData = $this->validateRequest($request);

        try {
            $page = ExtraPage::create($validatedData);
            Log::info('Extra page content created successfully.', ['id' => $page->id]);
            return redirect()->route('extraPage.index')
                             ->with('success', 'Page content created successfully.');
        } catch (Exception $e) {
            Log::error('Failed to create extra page content: ' . $e->getMessage()); // Use getMessage()
            return redirect()->back()->with('error', 'Failed to save content. Please check logs.')->withInput();
        }
    }

    public function update(Request $request, $id): RedirectResponse // Added return type hint
    {
        $validatedData = $this->validateRequest($request);

        try {
            // Use findOrFail to handle not found case
            $extraPage = ExtraPage::findOrFail($id);
            $extraPage->update($validatedData);

            Log::info('Extra page content updated successfully.', ['id' => $id]);
            return redirect()->route('extraPage.index')
                             ->with('success', 'Page content updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to update non-existent ExtraPage ID {$id}");
             return redirect()->back()->with('error', 'Page content not found.')->withInput();
        }
        catch (Exception $e) {
            Log::error("Failed to update extra page content (ID: {$id}): " . $e->getMessage()); // Use getMessage()
            return redirect()->back()->with('error', 'Failed to update content. Please check logs.')->withInput();
        }
    }
}
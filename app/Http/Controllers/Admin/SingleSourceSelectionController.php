<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EngageSection; // Use the new model
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Exception;

class SingleSourceSelectionController extends Controller
{
    use ImageUploadTrait;

    // The ID this controller will manage
    private const ENTRY_ID = 1;
    private const IMAGE_WIDTH = 500;
    private const IMAGE_HEIGHT = 504;
    private const UPLOAD_DIR = 'engage_section';

    /**
     * Display the form to create or update the 'Single Source Selection' entry.
     */
    public function index(): View
    {
        // Find the record with ID 1, or create a new instance if it doesn't exist
        $entry = EngageSection::findOrNew(self::ENTRY_ID);
        return view('admin.engage.sss.index', compact('entry'));
    }

    /**
     * Store or update the 'Single Source Selection' entry.
     */
    public function storeOrUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sort_description' => 'nullable|string',
            'image' => [
                'nullable', // Nullable if image is already set
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
        ]);

        try {
            // Find or create the entry
            $entry = EngageSection::findOrNew(self::ENTRY_ID);
            $entry->id = self::ENTRY_ID; // Force the ID to be 1
            $entry->title = $request->title;
$entry->sort_description = $request->sort_description;
            // Handle image update
            $imagePath = $this->handleImageUpdate(
                $request,
                $entry,
                'image',
                self::UPLOAD_DIR,
                self::IMAGE_WIDTH,
                self::IMAGE_HEIGHT
            );
            
            // Only update image if a new one was uploaded or it's a new record
            if ($request->hasFile('image') || !$entry->exists) {
                // If validation fails on a new record without an image
                if (empty($imagePath) && !$entry->image) {
                    return redirect()->back()->withInput()->with('error', 'A new entry requires an image.');
                }
                $entry->image = $imagePath;
            }

            $entry->save();
            
            Log::info('Engage Section (SSS) updated successfully.');
            return redirect()->back()->with('success', 'Entry updated successfully!');

        } catch (Exception $e) {
            Log::error('Failed to update SSS entry: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update entry. ' . $e->getMessage());
        }
    }
}
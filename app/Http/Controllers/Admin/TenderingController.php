<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EngageSection; // Use the same model
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Exception;

class TenderingController extends Controller
{
    use ImageUploadTrait;

    // The ID this controller will manage
    private const ENTRY_ID = 2; // <-- The only change
    private const IMAGE_WIDTH = 500;
    private const IMAGE_HEIGHT = 504;
    private const UPLOAD_DIR = 'engage_section';

    /**
     * Display the form to create or update the 'Tendering' entry.
     */
    public function index(): View
    {
        // Find the record with ID 2
        $entry = EngageSection::findOrNew(self::ENTRY_ID);
        return view('admin.engage.tendering.index', compact('entry')); // <-- Different view
    }

    /**
     * Store or update the 'Tendering' entry.
     */
    public function storeOrUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sort_description' => 'nullable|string',
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
        ]);

        try {
            // Find or create the entry
            $entry = EngageSection::findOrNew(self::ENTRY_ID);
            $entry->id = self::ENTRY_ID; // Force the ID to be 2
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

            if ($request->hasFile('image') || !$entry->exists) {
                if (empty($imagePath) && !$entry->image) {
                    return redirect()->back()->withInput()->with('error', 'A new entry requires an image.');
                }
                $entry->image = $imagePath;
            }

            $entry->save();
            
            Log::info('Engage Section (Tendering) updated successfully.');
            return redirect()->back()->with('success', 'Entry updated successfully!');

        } catch (Exception $e) {
            Log::error('Failed to update Tendering entry: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update entry. ' . $e->getMessage());
        }
    }
}
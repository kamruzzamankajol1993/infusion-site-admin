<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IifcStrength; // Import the model
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // This is already imported, which is good
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Cache; // Optional: For caching

class IifcStrengthController extends Controller
{
    /**
     * Permissions middleware.
     */
    public function __construct()
    {
        // Adjust permission names as needed
        $this->middleware('permission:iifcStrengthView', ['only' => ['index']]);
        $this->middleware('permission:iifcStrengthUpdate', ['only' => ['update']]);
    }

    /**
     * Display the IIFC Strength settings page.
     * Fetches the first (and only) record.
     */
    
    // *** THIS IS THE CORRECTED LINE ***
    public function index(): View|RedirectResponse
    {
        try {
            // Find the first record, or fail if table is empty (seed first!)
            $strength = IifcStrength::firstOrFail();
            return view('admin.iifc_strength.index', compact('strength'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::error('IIFC Strength record not found. Please seed the iifc_strengths table.');
            // Redirect with error or show a specific view asking to seed
             return redirect()->route('home')->with('error', 'IIFC Strength data not initialized. Please contact administrator.');
        } catch (Exception $e) {
            Log::error('Failed to load IIFC Strength page: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Could not load IIFC Strength settings.');
        }
    }

    /**
     * Update the existing IIFC Strength record.
     */
    public function update(Request $request, $id): RedirectResponse // id is expected from route
    {
        // Rules now match the IifcStrength model's fillable fields
        $validatedData = $request->validate([
            'projects' => 'required|integer|min:0',
            'products' => 'required|integer|min:0',
            'experts' => 'required|integer|min:0',
            'countries' => 'required|integer|min:0',
            'happy_clients' => 'required|integer|min:0',
            'yrs_experienced' => 'required|integer|min:0',
        ]);

        try {
            $strength = IifcStrength::findOrFail($id); // Find the specific record to update
            $strength->update($validatedData);

            // Optional: Clear cache if you cache these values
            // Cache::forget('iifc_strength_data');

            Log::info('IIFC Strength data updated successfully.', ['id' => $strength->id]);
            return redirect()->route('iifcStrength.index')->with('success', 'IIFC Strength data updated successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::error("Attempted to update non-existent IIFC Strength record ID {$id}");
             return redirect()->back()->withInput()->with('error', 'IIFC Strength record not found.');
        } catch (Exception $e) {
            Log::error('Failed to update IIFC Strength data: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update data. Please check logs.']);
        }
    }
}
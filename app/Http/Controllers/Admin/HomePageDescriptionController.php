<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePageDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class HomePageDescriptionController extends Controller
{
    /**
     * Show the form for editing the resource.
     */
    public function index()
    {
        try {
            $description = HomePageDescription::firstOrCreate(['id' => 1]);
            return view('admin.home_description.index', compact('description'));
        } catch (Exception $e) {
            Log::error('Failed to load home page description page: ' . $e);
            return redirect()->back()->with('error', 'Could not load the page.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        try {
            $description = HomePageDescription::find(1);
            if ($description) {
                $description->update($request->only('title', 'description'));
            }

            Log::info('Home page description updated successfully.');
            return redirect()->back()->with('success', 'Home page description updated successfully!');
            
        } catch (Exception $e) {
            Log::error('Failed to update home page description: ' . $e);
            return redirect()->back()->with('error', 'Failed to update content. Please check logs.');
        }
    }
}
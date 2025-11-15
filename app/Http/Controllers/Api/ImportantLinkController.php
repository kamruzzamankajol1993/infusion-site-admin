<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ImportantLink; // Import the model
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class ImportantLinkController extends Controller
{
    /**
     * Get a list of all important links.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Fetch all important links, order by title or ID if desired
            $links = ImportantLink::orderBy('title', 'asc')->get(['id', 'title', 'link']); // Select only needed columns

            // Return the collection wrapped in a 'data' key for consistency
            return response()->json([
                'data' => $links
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch Important Links: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve important links.'], 500);
        }
    }
}
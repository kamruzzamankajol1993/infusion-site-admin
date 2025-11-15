<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialLink; // Import the model
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class SocialLinkController extends Controller
{
    /**
     * Get a list of all social links.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Fetch all social links, order by title or ID if desired
            $links = SocialLink::orderBy('title', 'asc')->get(['id', 'title', 'link']); // Select only needed columns

            // No specific formatting needed, just return the collection
            return response()->json([
                'data' => $links
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch Social Links: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve social links.'], 500);
        }
    }
}
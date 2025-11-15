<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TopHeaderLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class TopHeaderLinkController extends Controller
{
    /**
     * Get the list of top header links.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Cache the result for performance
            $links = Cache::remember('top_header_links', 3600, function () {
                // Fetch only the two links and the columns we need
                return TopHeaderLink::whereIn('id', [1, 2])
                                    ->orderBy('id', 'asc')
                                    ->get(['title', 'link']);
            });

            return response()->json([
                'data' => $links
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch Top Header Links: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve links.'], 500);
        }
    }
}
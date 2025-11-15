<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client; // <-- 1. Import Client
use App\Models\SystemInformation; // <-- 2. Import SystemInformation
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; // <-- 3. Import Cache
use Exception;
use Illuminate\Http\Request;
class ClientController extends Controller
{
    /**
     * Get a list of all clients.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // --- 4. Get Base URL (Cached for Performance) ---
            $baseUrl = Cache::remember('system_main_url', 3600, function () {
                return SystemInformation::first()?->value('main_url');
            });

            if (empty($baseUrl)) {
                $baseUrl = config('app.url');
            }
            $baseUrl = rtrim($baseUrl, '/');
            

            // --- 5. Fetch all clients ---
            // You can add ->latest() if you want them in a specific order
            $clients = Client::whereNotNull('logo')->get();

            // --- 6. Format the output ---
            $formattedClients = $clients->map(function ($client) use ($baseUrl) {
                
                // a. Create the full logo_url
                $logoUrl = '';
                $logoPath = $client->logo;
                
                if (empty($logoPath)) {
                    // Using a generic placeholder, update if you have a specific one
                    $logoUrl = $baseUrl . '/public/No_Image_Available.jpg'; 
                } else {
                    $logoUrl = $baseUrl . '/' . ltrim($logoPath, '/');
                }

                // b. Return the desired fields
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'logo_url' => $logoUrl,
                ];
            });
            
            // --- 7. Return the formatted data ---
            return response()->json([
                'data' => $formattedClients
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch clients: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to retrieve clients.'
            ], 500);
        }
    }

    /**
     * --- UPDATED METHOD ---
     * Get a PAGINATED list of all clients, with search and per_page options.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function paginatedIndex(Request $request): JsonResponse
    {
        try {
            $baseUrl = Cache::remember('system_main_url', 3600, function () {
                return SystemInformation::first()?->value('main_url');
            });

            if (empty($baseUrl)) {
                $baseUrl = config('app.url');
            }
            $baseUrl = rtrim($baseUrl, '/');
            
            // --- 1. VALIDATE PER_PAGE ---
            $allowedPerPage = [10, 20, 30];
            $perPage = $request->input('per_page', 10);
            if (!in_array($perPage, $allowedPerPage)) {
                $perPage = 10; // Default to 10 if invalid value is sent
            }

            // --- 2. START QUERY ---
            $query = Client::whereNotNull('logo');

            // --- 3. ADD SEARCH ---
            if ($request->filled('search')) {
                $searchTerm = $request->input('search');
                $query->where('name', 'LIKE', '%' . $searchTerm . '%');
            }

            // --- 4. PAGINATE THE RESULT ---
            $clients = $query->latest()
                             ->paginate($perPage);

            // --- 5. FORMAT THE RESULTS (Unchanged) ---
            $clients->through(function ($client) use ($baseUrl) {
                
                $logoUrl = '';
                $logoPath = $client->logo;
                
                if (empty($logoPath)) {
                    $logoUrl = $baseUrl . '/public/No_Image_Available.jpg'; 
                } else {
                    $logoUrl = $baseUrl . '/' . ltrim($logoPath, '/');
                }

                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'logo_url' => $logoUrl,
                ];
            });

            // --- 6. APPEND QUERY PARAMETERS ---
            // This ensures pagination links include the search and per_page values
            // e.g., /?page=2&search=test&per_page=20
            $clients->appends($request->query());
            
            return response()->json($clients);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch paginated clients: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to retrieve paginated clients.'
            ], 500);
        }
    }
}
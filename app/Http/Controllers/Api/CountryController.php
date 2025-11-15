<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country; // <-- 1. Import Country model
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; // <-- 2. Import Request for pagination
use Illuminate\Support\Facades\Log;
use Exception;

class CountryController extends Controller
{

    public function indexWithProjects(): JsonResponse
    {
        try {
            // 1. Fetch all countries that have at least one project
            $countriesWithProjects = Country::whereHas('projects')
                                ->withCount('projects')
                                ->get();
            
            // 2. Fetch Bangladesh separately, to ensure it's included
            //    (as per the original file's special logic)
            $bangladesh = Country::where('name', 'Bangladesh')
                                 ->withCount('projects')
                                 ->first();
            
            // 3. Combine them and ensure no duplicates
            $allCountries = $countriesWithProjects;
            if ($bangladesh && !$allCountries->contains('id', $bangladesh->id)) {
                $allCountries->push($bangladesh);
            }

            // 4. Format the output
            $formattedCountries = $allCountries->map(function ($country) {
                return [
                    'name' => $country->name,
                    'iso3' => $country->iso3, // <-- ADDED
                    'projects_count' => $country->projects_count, // <-- The dynamic count
                ];
            });
            
            // 5. Return the formatted data
            return response()->json([
                'data' => $formattedCountries
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch countries with projects: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to retrieve country project data.'
            ], 500);
        }
    }
    /**
     * Get a list of all countries (no pagination).
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // 1. Fetch all countries, ordered by name
            $countries = Country::orderBy('name', 'asc')->get();

            // 2. Format the output (matching the 'data' wrapper from ClientController)
            $formattedCountries = $countries->map(function ($country) {
                return [
                    'id' => $country->id,
                    'name' => $country->name,
                    'iso3' => $country->iso3, // <-- ADDED
                ];
            });
            
            // 3. Return the formatted data
            return response()->json([
                'data' => $formattedCountries
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch countries: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to retrieve countries.'
            ], 500);
        }
    }

    /**
     * Get a PAGINATED list of all countries, with search and per_page options.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function paginatedIndex(Request $request): JsonResponse
    {
        try {
            // 1. VALIDATE PER_PAGE
            $allowedPerPage = [10, 20, 30, 50]; // Use the same logic as ClientController
            $perPage = $request->input('per_page', 10);
            if (!in_array($perPage, $allowedPerPage)) {
                $perPage = 10; // Default
            }

            // 2. START QUERY
            $query = Country::query()->whereHas('projects');

            // 3. ADD SEARCH (on the 'name' and 'iso3' columns)
            if ($request->filled('search')) {
                $searchTerm = $request->input('search');
                // --- MODIFIED SEARCH ---
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('iso3', 'LIKE', '%' . $searchTerm . '%'); // <-- ADDED
                });
            }

            // 4. PAGINATE THE RESULT
            // Sorting by name is more logical for countries than 'latest'
            $countries = $query->orderBy('name', 'asc')
                               ->paginate($perPage);

            // 5. FORMAT THE RESULTS (using 'through' for paginated collections)
            $countries->through(function ($country) {
                return [
                    'id' => $country->id,
                    'name' => $country->name,
                    'iso3' => $country->iso3, // <-- ADDED
                ];
            });

            // 6. APPEND QUERY PARAMETERS (for pagination links)
            $countries->appends($request->query());
            
            // 7. Return the paginated JSON response
            return response()->json($countries);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch paginated countries: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to retrieve paginated countries.'
            ], 500);
        }
    }
}
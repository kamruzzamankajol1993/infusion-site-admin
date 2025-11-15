<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;
// Import this for 404 handling
use Illuminate\Database\Eloquent\ModelNotFoundException; 

class CareerController extends Controller
{
    /**
     * Get a paginated list of all active career postings.
     * --- UPDATED ---
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);

            $careers = Career::where('application_deadline', '>=', now()->format('Y-m-d'))
                             ->orderBy('application_deadline', 'asc')
                             ->paginate($perPage);

            // Format the paginated data
            $careers->through(function ($item) {
                
                $shortDescription = Str::limit(strip_tags($item->description), 150);

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug, // <-- ADDED SLUG
                    'company_name' => $item->company_name,
                    'salary' => $item->salary,
                    'position' => $item->position,
                    'job_location' => $item->job_location,
                    'application_deadline' => $item->application_deadline,
                    'short_description' => $shortDescription,
                ];
            });

            return response()->json($careers);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch paginated careers: '. $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve careers.'], 500);
        }
    }

    /**
     * Get the detailed information for a single career posting.
     * --- UPDATED ---
     *
     * @param string $slug The career slug from the URL.
     * @return JsonResponse
     */
    public function show(string $slug): JsonResponse // <-- 1. Changed parameter
    {
        try {
            // 2. Find by slug instead of ID
            $career = Career::where('slug', $slug)->firstOrFail(); 

            // 3. Check deadline (unchanged, but now runs after finding)
            if ($career->application_deadline < now()->format('Y-m-d')) {
                 return response()->json(['error' => 'This job posting has expired.'], 404);
            }

            // 4. Format the final output
            $formattedData = [
                'id' => $career->id,
                'title' => $career->title,
                'slug' => $career->slug, // <-- ADDED SLUG
                'company_name' => $career->company_name,
                'position' => $career->position,
                'qualification' => $career->qualification,
                'age' => $career->age,
                'salary' => $career->salary,
                'experience' => $career->experience,
                'job_location' => $career->job_location,
                'description' => $career->description,
                'application_deadline' => $career->application_deadline,
                'email' => $career->email,
            ];

            return response()->json(['data' => $formattedData]);

        // 5. Add a specific catch for not found
        } catch (ModelNotFoundException $e) {
            Log::warning("API: Career not found for slug '{$slug}'");
            return response()->json(['error' => 'Career not found.'], 404);
        } catch (Exception $e) {
            Log::error("API: Failed to fetch career detail for slug '{$slug}': " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve career details.'], 500);
        }
    }
}
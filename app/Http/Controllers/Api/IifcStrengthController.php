<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// Removed: use App\Models\IifcStrength;
use App\Models\Project;   // <-- Import Project model
use App\Models\Country;   // <-- Import Country model
use Carbon\Carbon;        // <-- Import Carbon for date calculations
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // <-- Import DB facade for efficient counting
use Exception;

class IifcStrengthController extends Controller
{
    /**
     * Get the company's IIFC strength statistics dynamically.
     *
     * Calculates:
     * - Ongoing projects count from the Project table.
     * - Completed projects count from the Project table.
     * - Total active countries from the Country table.
     * - Years of experience since the year 2000.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // 1. Get project counts by status (efficiently)
            $projectCounts = Project::select('status', DB::raw('count(*) as total'))
                                    ->whereIn('status', ['ongoing', 'complete'])
                                    ->groupBy('status')
                                    ->pluck('total', 'status'); // Returns ['ongoing' => count, 'complete' => count]

            $ongoingProjects = $projectCounts->get('ongoing', 0); // Get count for 'ongoing', default to 0 if none
            $completedProjects = $projectCounts->get('complete', 0); // Get count for 'complete', default to 0 if none

            // 2. Get active country count
            $countryCount = Country::where('status', true)->whereHas('projects')->count(); // Counting only active countries

            // 3. Calculate years of experience
            $startYear = 2000;
            $currentYear = Carbon::now()->year;
            $yearsExperience = $currentYear - $startYear;

            // Prepare the response data
            $strengthsData = [
                'ongoing_projects' => $ongoingProjects,     // <-- Updated key
                'completed_projects' => $completedProjects, // <-- Updated key
                'countries' => $countryCount,
                'experience' => $yearsExperience,
                // Add any other fixed fields if needed
            ];

            // Return the calculated data, wrapped in a 'data' key
            return response()->json([
                'data' => $strengthsData
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch dynamic IIFC strengths: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to retrieve strength information.'
            ], 500); // 500 Internal Server Error
        }
    }
}
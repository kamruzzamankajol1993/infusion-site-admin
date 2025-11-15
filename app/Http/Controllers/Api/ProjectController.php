<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory; // <-- 1. Add this import
use App\Models\SystemInformation;
use Illuminate\Http\Request; // <-- 2. Add this import
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str; // <-- 3. Add this import
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class ProjectController extends Controller
{


    public function getActiveProjectYears(): JsonResponse
    {
        try {
            $years = Project::whereNotNull('agreement_signing_date')
                            ->selectRaw('YEAR(agreement_signing_date) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');
            
            return response()->json(['data' => $years]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch active project years: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve project years.'], 500);
        }
    }

 

    /**
     * Get a paginated list of all projects.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $baseUrl = $this->getBaseUrl();
            $perPage = $request->input('per_page', 12); // Default to 12 per page

            // --- UPDATED QUERY ---
            // Eager load all relationships used in the summary
            $query = Project::with(['category', 'country', 'galleryImages', 'client']);

            // --- 1. ADD SEARCH LOGIC ---
            if ($request->filled('search')) {
                $searchTerm = $request->input('search');
                // Search against multiple relevant fields
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%')
                      ->orWhere('service', 'like', '%' . $searchTerm . '%') // Include the new 'service' column
                      ->orWhereHas('category', function($catQuery) use ($searchTerm) {
                          $catQuery->where('name', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('client', function($clientQuery) use ($searchTerm) {
                          $clientQuery->where('name', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('country', function($countryQuery) use ($searchTerm) {
                          $countryQuery->where('name', 'like', '%' . $searchTerm . '%');
                      });
                });
            }
            // --- END SEARCH LOGIC ---

            // --- 2. ADD STATUS FILTER LOGIC ---
            if ($request->filled('status')) {
                $status = $request->input('status');
                // You could add validation here to ensure status is one of ['pending', 'ongoing', 'complete']
                $query->where('status', $status);
            }
            // --- END STATUS FILTER LOGIC ---

            // --- 3. ADD YEAR FILTER LOGIC ---
            if ($request->filled('year')) {
                $year = $request->input('year');
                if (is_numeric($year)) {
                    // This efficiently queries the YEAR part of the date column
                    $query->whereYear('agreement_signing_date', $year);
                }
            }
            // --- END YEAR FILTER LOGIC ---

            $projects = $query->orderBy('id','asc') // Order by newest
                           ->paginate($perPage);

            // --- Append query strings (like status, search) to pagination links ---
            $projects->appends($request->query());
            // --- END UPDATED QUERY ---

            // Format the paginated data
            $projects->through(function ($project) use ($baseUrl) {
                return $this->formatProjectSummary($project, $baseUrl);
            });

            return response()->json($projects);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch paginated projects: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve projects.'], 500);
        }
    }


    public function flagshipProjectsAll(Request $request){


        try {
            $baseUrl = $this->getBaseUrl();
            $perPage = $request->input('per_page', 12); // Default to 12 per page

            // --- UPDATED QUERY ---
            // Eager load all relationships used in the summary
            $query = Project::with(['category', 'country', 'galleryImages', 'client'])
            ->where('is_flagship', true);

            // --- 1. ADD SEARCH LOGIC ---
            if ($request->filled('search')) {
                $searchTerm = $request->input('search');
                // Search against multiple relevant fields
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%')
                      ->orWhere('service', 'like', '%' . $searchTerm . '%') // Include the new 'service' column
                      ->orWhereHas('category', function($catQuery) use ($searchTerm) {
                          $catQuery->where('name', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('client', function($clientQuery) use ($searchTerm) {
                          $clientQuery->where('name', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('country', function($countryQuery) use ($searchTerm) {
                          $countryQuery->where('name', 'like', '%' . $searchTerm . '%');
                      });
                });
            }
            // --- END SEARCH LOGIC ---

            // --- 2. ADD STATUS FILTER LOGIC ---
            if ($request->filled('status')) {
                $status = $request->input('status');
                // You could add validation here to ensure status is one of ['pending', 'ongoing', 'complete']
                $query->where('status', $status);
            }
            // --- END STATUS FILTER LOGIC ---

            // --- 3. ADD YEAR FILTER LOGIC ---
            if ($request->filled('year')) {
                $year = $request->input('year');
                if (is_numeric($year)) {
                    // This efficiently queries the YEAR part of the date column
                    $query->whereYear('agreement_signing_date', $year);
                }
            }
            // --- END YEAR FILTER LOGIC ---

            $projects = $query->orderBy('id','asc') // Order by newest
                           ->paginate($perPage);

            // --- Append query strings (like status, search) to pagination links ---
            $projects->appends($request->query());
            // --- END UPDATED QUERY ---

            // Format the paginated data
            $projects->through(function ($project) use ($baseUrl) {
                return $this->formatProjectSummary($project, $baseUrl);
            });

            return response()->json($projects);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch paginated projects: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve projects.'], 500);
        }


    }

    /**
     * Get a paginated list of projects for a specific category.
     *
     * @param Request $request
     * @param ProjectCategory $category (Injected by Route Model Binding)
     * @return JsonResponse
     */
    public function showByCategory(Request $request, string $slug): JsonResponse
    {
        try {
            // --- 1. Find the category by its slug ---
            $category = ProjectCategory::where('slug', $slug)->firstOrFail();
            // --- End Find ---

            $baseUrl = $this->getBaseUrl();
            $perPage = $request->input('per_page', 12);

            // --- 2. Use the found category's ID for the query ---
            $query = Project::where('category_id', $category->id)
                               ->with(['country', 'galleryImages', 'client']); // No need to load 'category'

            // --- (Search Logic - Unchanged) ---
            if ($request->filled('search')) {
                $searchTerm = $request->input('search');
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%')
                      ->orWhere('service', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('client', function($clientQuery) use ($searchTerm) {
                          $clientQuery->where('name', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('country', function($countryQuery) use ($searchTerm) {
                          $countryQuery->where('name', 'like', '%' . $searchTerm . '%');
                      });
                });
            }
            // --- END SEARCH LOGIC ---

            // --- (Status Filter Logic - Unchanged) ---
            if ($request->filled('status')) {
                $status = $request->input('status');
                $query->where('status', $status);
            }
            // --- END STATUS FILTER LOGIC ---

            // --- 3. ADD YEAR FILTER LOGIC ---
            if ($request->filled('year')) {
                $year = $request->input('year');
                if (is_numeric($year)) {
                    // This efficiently queries the YEAR part of the date column
                    $query->whereYear('agreement_signing_date', $year);
                }
            }
            // --- END YEAR FILTER LOGIC ---

            $projects = $query->orderBy('id','asc')
                               ->paginate($perPage);
            
            $projects->appends($request->query());

            // Format the paginated data
            $projects->through(function ($project) use ($baseUrl, $category) {
                // Pass the already-found category to the formatter
                return $this->formatProjectSummary($project, $baseUrl, $category);
            });

            return response()->json($projects);
        
        // --- 3. Add catch block for invalid slug ---
        } catch (ModelNotFoundException $e) {
            Log::warning("API: Project category not found for slug '{$slug}'");
            return response()->json(['error' => 'Category not found.'], 404);
        } catch (Exception $e) {
            Log::error("API: Failed to fetch projects for category slug '{$slug}': " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve projects.'], 500);
        }
    }

    /**
     * Get the detailed information for a single project using its slug.
     *
     * @param string $slug The project slug from the URL.
     * @return JsonResponse
     */
    public function show(string $slug): JsonResponse // <-- Changed parameter
    {
        try {
            $baseUrl = $this->getBaseUrl();

            // --- Find project by slug ---
            $project = Project::where('slug', $slug)->firstOrFail();
            // --- End find ---

            // Eager-load all details for this single project
            $project->load(['category', 'country', 'client', 'galleryImages']);

            // Get all gallery images as full URLs
            $gallery = $project->galleryImages->map(function ($img) use ($baseUrl) {
                $path = $img->image_path;
                // Assuming image_path is relative to public folder
                return empty($path) ? null : asset($path); // Use asset helper
            })->filter(); // ->filter() removes any null entries

            // Format the final output
            $formattedData = [
                'id' => $project->id, // Keep ID in response
                'title' => $project->title,
                'slug' => $project->slug, // <-- Include slug in response
                   'service' => $project->service,
                'description' => $project->description, // Full, clean description
                'client_name' => $project->client?->name ?? 'N/A',
                'country_name' => $project->country?->name ?? 'N/A',
                'category_name' => $project->category?->name ?? 'N/A',
                'agreement_signing_date' => $project->agreement_signing_date,
                'status' => $project->status,
                'is_flagship' => (bool) $project->is_flagship, // <-- Added is_flagship (cast to boolean)
                'gallery' => $gallery,
            ];

            return response()->json(['data' => $formattedData]);

        // Catch ModelNotFoundException specifically for a nicer 404 message
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("API: Project not found for slug '{$slug}'");
            return response()->json(['error' => 'Project not found.'], 404);
        } catch (Exception $e) {
            Log::error("API: Failed to fetch project detail for slug '{$slug}': " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve project details.'], 500); // Use 500 for general errors
        }
    }
    /**
     * Get the latest 6 flagship projects for the home page.
     *
     * @return JsonResponse
     */
    public function flagshipProjects(): JsonResponse
    {
        try {
            // --- Get Base URL (Cached for Performance) ---
            $baseUrl = Cache::remember('system_main_url', 3600, function () {
                return SystemInformation::first()?->value('main_url');
            });

            if (empty($baseUrl)) {
                $baseUrl = config('app.url');
            }
            $baseUrl = rtrim($baseUrl, '/');
            

            // --- Fetch latest 6 flagship projects ---
            $projects = Project::where('is_flagship', true)
                               ->with(['category', 'country', 'galleryImages']) // Eager load
                               ->latest() // Orders by 'created_at' DESC
                               ->take(6)
                               ->get();

            // --- Format the output ---
            $formattedProjects = $projects->map(function ($project) use ($baseUrl) {
                
                // a. Get the first image from the gallery
                $firstImage = $project->galleryImages->first();
                
                // UPDATED: Use 'image_path' from ProjectGallery model
                $imagePath = $firstImage ? $firstImage->image_path : null; 
                
                $coverImageUrl = '';
                if (empty($imagePath)) {
                    $coverImageUrl = $baseUrl . '/public/No_Image_Available.jpg';
                } else {
                    $coverImageUrl = $baseUrl . '/' . ltrim($imagePath, '/');
                }

                // b. Get relationship data
                
                // UPDATED: Use 'name' from ProjectCategory model
                $categoryName = $project->category ? $project->category->name : 'Uncategorized'; 
                
                // CONFIRMED: Use 'name' from Country model
                $countryName = $project->country ? $project->country->name : 'Unknown';

                // c. Return the desired fields
                return [
                    'id' => $project->id,
                    'title' => $project->title,
                     'slug' => $project->slug,
                     'service' => $project->service,
                    'category' => $categoryName,
                    'country' => $countryName,
                    'cover_image_url' => $coverImageUrl,
                ];
            });
            
            // --- Return the formatted data ---
            return response()->json([
                'data' => $formattedProjects
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch flagship projects: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to retrieve projects.'
            ], 500);
        }
    }


    private function getBaseUrl(): string
    {
        // Use the full namespace for SystemInformation
        $baseUrl = \Illuminate\Support\Facades\Cache::remember('system_main_url', 3600, function () {
            return \App\Models\SystemInformation::first()?->value('main_url');
        });

        if (empty($baseUrl)) {
            $baseUrl = config('app.url');
        }
        return rtrim($baseUrl, '/');
    }


    private function formatProjectSummary(\App\Models\Project $project, string $baseUrl, $category = null): array
    {
        // a. Get cover image URL
        $coverImageUrl = '';
        // Eager loading should prevent N+1 here if done correctly in the calling method
        $firstImage = $project->galleryImages->first();
        $imagePath = $firstImage ? $firstImage->image_path : null;

        if (empty($imagePath)) {
            // Use a standard public path for the default image
            $coverImageUrl = $baseUrl . '/public/No_Image_Available.jpg';
        } else {
            // Assume image_path is like 'public/uploads/...'
            $coverImageUrl = asset($imagePath); // Use asset helper
        }

        // b. Get relationship data (use pre-loaded category if available)
        $categoryName = $category ? $category->name : ($project->category?->name ?? 'Uncategorized');
        $countryName = $project->country?->name ?? 'Unknown';
        $clientName = $project->client?->name ?? 'N/A'; // Added client name

        // c. Clean and truncate description
        // Use Illuminate\Support\Str for string limiting
        $shortDescription = \Illuminate\Support\Str::limit(strip_tags($project->description), 300);

        // d. Return formatted array including all necessary fields for list views
        return [
            'id' => $project->id,
            'title' => $project->title,
            'slug' => $project->slug,
            'service' => $project->service,
            'description' => $shortDescription, // Use the short description
            'category_name' => $categoryName,
            'country' => $countryName,
            'client_name' => $clientName, // Include client name
            'cover_image_url' => $coverImageUrl,
            'agreement_signing_date' => $project->agreement_signing_date,
            'status' => $project->status, // Include status if it exists on the model
        ];
    }

    public function ongoingProjects(Request $request): JsonResponse
    {
        // Force the 'status' parameter to 'ongoing'
        $request->merge(['status' => 'ongoing']);

        // Call the main index method with the modified request
        return $this->index($request);
    }


    public function completeProjects(Request $request): JsonResponse
    {
        // Force the 'status' parameter to 'complete'
        $request->merge(['status' => 'complete']);

        // Call the main index method with the modified request
        return $this->index($request);
    }
}
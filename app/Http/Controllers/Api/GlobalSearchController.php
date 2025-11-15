<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\Event;
use App\Models\Training;
use App\Models\Career;
use App\Models\Service;
use App\Models\Notice;
use App\Models\Publication;
use App\Models\SystemInformation; // <-- 1. ADD THIS
use Illuminate\Support\Facades\Cache; // <-- 2. ADD THIS

class GlobalSearchController extends Controller
{
    /**
     * Handle a global search request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        // 1. Validate the search query
        $validated = $request->validate([
            'query' => 'required|string|min:3|max:100',
        ]);

        $term = $validated['query'];
        $results = collect();
        
        // --- 3. GET BASE URL ---
        $baseUrl = $this->getBaseUrl();

        // --- 2. Query Each Model ---

        // Projects
        $projects = Project::where('title', 'LIKE', "%{$term}%")
                           ->orWhere('description', 'LIKE', "%{$term}%")
                           ->orWhere('service', 'LIKE', "%{$term}%")
                           ->limit(10)->get();
        
        $results = $results->merge($projects->map(function($item) {
            return [
                'type' => 'Project',
                'title' => $item->title,
                'link' => '/project/' . $item->slug, // Front-end page link
                'description' => Str::limit(strip_tags($item->description), 150)
            ];
        }));

        // Events (Only published)
        $events = Event::where('status', 1)
                       ->where(function($q) use ($term) {
                           $q->where('title', 'LIKE', "%{$term}%")
                             ->orWhere('description', 'LIKE', "%{$term}%");
                       })
                       ->limit(10)->get();

        $results = $results->merge($events->map(function($item) {
            return [
                'type' => 'Event',
                'title' => $item->title,
                'link' => '/event/' . $item->slug, // Front-end page link
                'description' => Str::limit(strip_tags($item->description), 150)
            ];
        }));

        // Trainings
        $trainings = Training::where('status', '!=', 'postponed') // Example: ignore postponed
                           ->where(function($q) use ($term) {
                               $q->where('title', 'LIKE', "%{$term}%")
                                 ->orWhere('description', 'LIKE', "%{$term}%")
                                 ->orWhere('learn_from_training', 'LIKE', "%{$term}%");
                           })
                           ->limit(10)->get();
        
        $results = $results->merge($trainings->map(function($item) {
            return [
                'type' => 'Training',
                'title' => $item->title,
                'link' => '/training/' . $item->slug, // Front-end page link
                'description' => Str::limit(strip_tags($item->description), 150)
            ];
        }));

        // Careers (Only active)
        $careers = Career::where('application_deadline', '>=', now()->format('Y-m-d'))
                         ->where(function($q) use ($term) {
                             $q->where('title', 'LIKE', "%{$term}%")
                               ->orWhere('position', 'LIKE', "%{$term}%")
                               ->orWhere('description', 'LIKE', "%{$term}%");
                         })
                         ->limit(10)->get();
        
        $results = $results->merge($careers->map(function($item) {
            return [
                'type' => 'Career',
                'title' => $item->title . ' (' . $item->position . ')',
                'link' => '/career/' . $item->slug, // Front-end page link
                'description' => Str::limit(strip_tags($item->description), 150)
            ];
        }));

        // Services
        $services = Service::where('title', 'LIKE', "%{$term}%")
                           ->orWhere('description', 'LIKE', "%{$term}%")
                           ->limit(10)->get();
        
        $results = $results->merge($services->map(function($item) {
            return [
                'type' => 'Service',
                'title' => $item->title,
                'link' => null, // <-- Set to null as requested
                'description' => Str::limit(strip_tags($item->description), 150)
            ];
        }));

        // Notices (PDFs)
        $notices = Notice::where('title', 'LIKE', "%{$term}%")
                         ->limit(10)->get();
        
        $results = $results->merge($notices->map(function($item) use ($baseUrl) { // <-- 4. Pass $baseUrl
            $pdfUrl = $this->getPdfUrl($baseUrl, $item->pdf_file); // <-- 5. Generate URL
            return [
                'type' => 'Notice',
                'title' => $item->title,
                'link' => $pdfUrl, // <-- 6. Use the full URL
                'description' => 'Notice published on ' . $item->date
            ];
        }));

        // Publications (PDFs)
        $publications = Publication::where('title', 'LIKE', "%{$term}%")
                                 ->orWhere('description', 'LIKE', "%{$term}%")
                                 ->limit(10)->get();
        
        $results = $results->merge($publications->map(function($item) use ($baseUrl) { // <-- 7. Pass $baseUrl
            $pdfUrl = $this->getPdfUrl($baseUrl, $item->pdf_file); // <-- 8. Generate URL
            return [
                'type' => 'Publication',
                'title' => $item->title,
                'link' => $pdfUrl, // <-- 9. Use the full URL
                'description' => Str::limit(strip_tags($item->description), 150)
            ];
        }));

        // 3. Sort and return the final list
        $sortedResults = $results->sortBy('title')->values();

        return response()->json(['data' => $sortedResults]);
    }

    // --- 10. ADD HELPER METHODS ---

    /**
     * Helper to get the cached base URL.
     */
    private function getBaseUrl(): string
    {
        $baseUrl = Cache::remember('system_main_url', 3600, function () {
            return SystemInformation::first()?->value('main_url');
        });

        if (empty($baseUrl)) {
            $baseUrl = config('app.url');
        }
        return rtrim($baseUrl, '/');
    }

    /**
     * Helper to build a full PDF URL.
     */
    private function getPdfUrl(string $baseUrl, ?string $pdfPath): ?string
    {
        if (empty($pdfPath)) {
            return null;
        }
        // Assumes $pdfPath is like 'public/uploads/...' or 'uploads/...'
        return $baseUrl . '/' . ltrim($pdfPath, '/');
    }
}


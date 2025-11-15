<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeCategory; // <-- 1. Import
use App\Models\SystemInformation;
use Illuminate\Http\Request;      // <-- 2. Import
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;
use Illuminate\Support\Carbon;
class NoticeController extends Controller
{
    /**
     * Get a paginated list of notices for a specific category.
     *
     * @param Request $request
     * @param NoticeCategory $category (Injected by Route Model Binding)
     * @return JsonResponse
     */
    public function showByCategory(Request $request, NoticeCategory $category): JsonResponse
    {
        try {
            $baseUrl = $this->getBaseUrl();
            $perPage = $request->input('per_page', 15); // Default to 15 per page

            // Query notices for this category, order by date
            $notices = Notice::where('category_id', $category->id)
                                ->latest('date') // Order by date, newest first
                                ->paginate($perPage);

            // Format the paginated data
            $notices->through(function ($notice) use ($baseUrl) {
                
                // Create the full, absolute URL for the PDF
                $pdfUrl = '';
                if (!empty($notice->pdf_file)) {
                    $pdfUrl = SystemInformation::first()?->value('main_url') . 'public/' . ltrim($notice->pdf_file, '/');
                }

                return [
                    'id' => $notice->id,
                    'title' => $notice->title,
                    'date' => $notice->date,
                    'pdf_url' => $pdfUrl, // This is the URL your React app will use
                ];
            });

            return response()->json($notices);

        } catch (Exception $e) {
            Log::error("API: Failed to fetch notices for category {$category->id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve notices.'], 500);
        }
    }


    public function latestNotices(): JsonResponse
    {
        try {
            $baseUrl = $this->getBaseUrl();
$thirtyDaysAgo = Carbon::now()->subDays(30);
            // Fetch the latest 20 notices ordered by date
   $notices = Notice::with('category')
                            ->where('date', '>=', $thirtyDaysAgo) // <-- Filter is applied here
                            ->latest('date')
                            ->get();

            // Format the data
            $formattedNotices = $notices->map(function ($notice) use ($baseUrl) {
                
                // Create the full, absolute URL for the PDF
                $pdfUrl = '';
                if (!empty($notice->pdf_file)) {
                    $pdfUrl = SystemInformation::first()?->value('main_url') . 'public/' . ltrim($notice->pdf_file, '/');
                }

                return [
                    'id' => $notice->id,
                    'title' => $notice->title,
                    'date' => $notice->date,
                    'pdf_url' => $pdfUrl, // This is the URL your React app will use
                    'category_name' => $notice->category?->name ?? 'Uncategorized',
                ];
            });

            return response()->json([
                'data' => $formattedNotices
            ]);

        } catch (Exception $e) {
            Log::error("API: Failed to fetch latest notices: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve latest notices.'], 500);
        }
    }


    // --- Helper Method ---

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
}
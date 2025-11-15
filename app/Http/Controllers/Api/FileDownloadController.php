<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Download; // Use the Download model
use App\Models\SystemInformation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

// The new controller name you requested
class FileDownloadController extends Controller
{
    /**
     * Get a paginated list of all downloads.
     * This will be used by the /file_download_list route.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $baseUrl = $this->getBaseUrl();
            $perPage = $request->input('per_page', 15); // Default to 15 per page

            // Query all downloads, order by date
            $downloads = Download::latest('date') // Order by date, newest first
                                ->paginate($perPage);

            // Format the paginated data
            $downloads->through(function ($download) use ($baseUrl) {
                
                // Create the full, absolute URL for the PDF
                $pdfUrl = '';
                if (!empty($download->pdf_file)) {
                    // Construct the full URL
                    $pdfUrl = $this->getBaseUrl() . '/public/' . ltrim($download->pdf_file, '/');
                }

                return [
                    'id' => $download->id,
                    'title' => $download->title,
                    'date' => $download->date,
                    'pdf_url' => $pdfUrl, // This is the URL your React app will use
                ];
            });

            return response()->json($downloads);

        } catch (Exception $e) {
            Log::error("API: Failed to fetch downloads: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve downloads.'], 500);
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
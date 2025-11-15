<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\SystemInformation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Exception;

class PublicationController extends Controller
{
    /**
     * Get a paginated list of all publications.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $baseUrl = $this->getBaseUrl();
            $perPage = $request->input('per_page', 10);

            $publications = Publication::latest('date')->paginate($perPage);

            $publications->through(function ($item) use ($baseUrl) {
                
                // This call uses $type = 'image'
                $imageUrl = $this->getFileUrl($baseUrl, $item->image, 'image');
                
                // This call uses the default $type = 'file'
                $pdfUrl = $this->getFileUrl($baseUrl, $item->pdf_file);

                $shortDescription = Str::limit(strip_tags($item->description), 150);

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'date' => $item->date,
                    'short_description' => $shortDescription,
                    'image_url' => $imageUrl,
                    'pdf_url' => $pdfUrl,
                ];
            });

            return response()->json($publications);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch paginated publications: '. $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve publications.'], 500);
        }
    }

    // --- Helper Methods ---

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
     * Helper to build a full file URL (for PDF or Image).
     * This function now correctly handles your different path structures.
     */
    private function getFileUrl(string $baseUrl, ?string $filePath, string $type = 'file'): ?string
    {
        if (empty($filePath)) {
            // Return a default placeholder for images, null for other files
            return $type === 'image' ? $baseUrl . '/public/No_Image_Available.jpg' : null;
        }

        $finalPath = $filePath;

        // Your PDF path is: 'uploads/publications/pdfs/...'
        // Your Image path is: 'public/uploads/publications/images/...'
        
        // If the type is NOT 'image' (so it's the PDF)
        // AND the path does NOT already start with 'public/',
        // we must add it.
        if ($type !== 'image' && !str_starts_with($filePath, 'public/')) {
            $finalPath = 'public/' . ltrim($filePath, '/');
        }
        
        // Now both paths will be correct before being passed to asset():
        // asset('public/uploads/publications/images/...')
        // asset('public/uploads/publications/pdfs/...')
        return asset($finalPath);
    }
}
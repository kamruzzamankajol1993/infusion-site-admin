<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\File; // Import File facade
use Illuminate\Support\Str; // Import Str facade
use Symfony\Component\HttpFoundation\BinaryFileResponse; // For return type hint

class DownloadController extends Controller
{


    // This method is already correct and handles the 'Download' type
    public function downloadFile(string $type, int $id): BinaryFileResponse|JsonResponse
    {
        try {
            $record = null;
            $filePathColumn = 'pdf_file'; 

            if ($type === 'Notice') {
                $record = DB::table('notices')->find($id);
            } elseif ($type === 'Publication') {
                $record = DB::table('publications')->find($id);
            } elseif ($type === 'Download') { // <-- This correctly handles downloads
                $record = DB::table('downloads')->find($id);
            } else {
                return response()->json(['error' => 'Invalid download type specified.'], 400);
            }

            if (!$record) {
                Log::warning("API: Download record not found for type '{$type}' and ID {$id}");
                return response()->json(['error' => 'File record not found.'], 404);
            }

            $storedPath = $record->$filePathColumn;
            if (empty($storedPath)) {
                 Log::warning("API: File path is empty for type '{$type}' and ID {$id}");
                 return response()->json(['error' => 'File path not specified for this record.'], 404);
            }

            $relativePath = $storedPath;
            if (Str::startsWith($relativePath, 'public/')) {
                 $relativePath = Str::after($relativePath, 'public/');
            } elseif (Str::startsWith($relativePath, '/public/')) {
                 $relativePath = Str::after($relativePath, '/public/');
            }
            $fullPath = public_path($relativePath);

            if (!File::exists($fullPath)) {
                Log::error("API: File not found on server at path: {$fullPath} for type '{$type}' ID {$id} (Stored path: '{$storedPath}')");
                return response()->json(['error' => 'File not found on server.'], 404);
            }
            
            $fileName = Str::slug($record->title ?: $type.'_'.$id) . '_' . ($record->date ? Str::slug($record->date) : time()) . '.' . File::extension($fullPath);

            return response()->download($fullPath, $fileName);

        } catch (Exception $e) {
            Log::error("API: Failed to download file for type '{$type}' ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Could not process download request.'], 500);
        }
    }
    
    /**
     * Get a paginated list of all downloadable files
     * (Notices, Publications, AND Downloads)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 20); // 20 items per page

            // --- UPDATED QUERY ---
            // 1. Create a query ONLY for Downloads
            $downloadsQuery = DB::table('downloads')
                         ->select('id', 'title', 'date', 'pdf_file', DB::raw("'Download' as type"))
                         ->whereNotNull('pdf_file');
            
            // 2. Order by date and paginate
            $paginatedResults = $downloadsQuery->orderBy('date', 'desc') // Newest first
                                              ->paginate($perPage);
            // --- END UPDATED QUERY ---


            // 3. Format the paginated results to create correct 'pdf_url'
            $paginatedResults->getCollection()->transform(function ($item) {

                $pdfUrl = null;
                if (!empty($item->pdf_file)) {
                    $finalPath = $item->pdf_file;

                    // Add 'public/' prefix if it's missing
                    if (!str_starts_with($finalPath, 'public/')) {
                        $finalPath = 'public/' . ltrim($finalPath, '/');
                    }

                    $pdfUrl = asset($finalPath);
                }

                unset($item->pdf_file);
                $item->pdf_url = $pdfUrl;

                return $item;
            });

            // 4. Return the final paginated JSON
            return response()->json($paginatedResults);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch downloads list: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve downloads.'], 500);
        }
    }
}